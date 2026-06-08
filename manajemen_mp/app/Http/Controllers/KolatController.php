<?php

namespace App\Http\Controllers;

use App\Models\Kolat;
use Illuminate\Http\Request;

class KolatController extends Controller
{
    // 1. Tampilkan Halaman Utama Kolat
    public function kolat()
    {
       $data_kolat = Kolat::all();
        return view('kolat.kolat', compact('data_kolat'));
    }

    // membuat data dan Simpan Data Kolat Baru
    public function create()
    {
        return view('kolat.kolatcreate');
    }

    public function store(Request $request)
{

    $request->validate([
        'nama_kolat' => 'required|unique:kolat,nama_kolat',
        'alamat_kolat' => 'nullable'
    ]);


    Kolat::create($request->all());

    return redirect()->route('kolat.index')->with('success', 'Kolat baru berhasil ditambahkan!');
}

    public function edit($id)
{

    $kolat = Kolat::findOrFail($id);
    return view('kolat.editkolat', compact('kolat'));
}
public function update(Request $request, $id)
{

    $request->validate([
        'nama_kolat' => 'required|unique:kolat,nama_kolat,'.$id,
        'alamat_kolat' => 'nullable'
    ]);

    $kolat = Kolat::findOrFail($id);

    $kolat->update($request->all());
    return redirect()->route('kolat.index')->with('success', 'Data Kolat berhasil diperbarui!');
}

    // 4. Hapus Data Kolat
    // public function destroy($id)
    // {
    //     $kolat = Kolat::findOrFail($id);
    //     $kolat->delete();
    //     return redirect()->back()->with('success', 'Kolat telah dihapus!');
    // }

    public function destroy($id)
    {
        $kolat = Kolat::findOrFail($id);

        // Cek apakah ada anggota di kolat ini
        if ($kolat->anggota()->count() > 0) {
            return redirect()->back()->with('error', 'Kolat tidak bisa dihapus karena masih ada anggota yang terdaftar di dalamnya!');
        }

        $kolat->delete();
        return redirect()->back()->with('success', 'Kolat telah dihapus!');
    }
}
