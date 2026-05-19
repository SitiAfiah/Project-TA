<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Menampilkan form profil anggota yang sedang login.
     */
    public function edit()
    {
        // Mengambil data anggota yang berelasi dengan User yang sedang login
        $anggota = Auth::user()->anggota;

        if (!$anggota) {
            return redirect()->back()->with('error', 'Data profil tidak ditemukan.');
        }

        return view('profil.edit', compact('anggota'));
    }

    /**
     * Menyimpan perubahan data yang diperbaiki anggota.
     */
    public function update(Request $request)
    {
        $anggota = Auth::user()->anggota;

        // 1. Validasi input, tambahkan validasi untuk foto
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_hp'        => 'required|numeric',
            'tempat_lahir' => 'required|string',
            'tgl_lahir'    => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat'       => 'required|string',
            'foto_profil'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        // 2. Ambil data teks
        $input = $request->only([
            'nama_lengkap',
            'no_hp',
            'tempat_lahir',
            'tgl_lahir',
            'jenis_kelamin',
            'alamat'
        ]);

        // 3. Logika Upload Foto
        if ($request->hasFile('foto_profil')) {
            // Jika anggota sudah punya foto sebelumnya, hapus file lamanya dari storage
            if ($anggota->foto_profil && Storage::disk('public')->exists($anggota->foto_profil)) {
                Storage::disk('public')->delete($anggota->foto_profil);
            }

            // Simpan foto baru ke folder 'profil_images' di dalam storage/app/public
            $path = $request->file('foto_profil')->store('profil_images', 'public');

            // Masukkan path foto baru ke dalam array input untuk disimpan ke database
            $input['foto_profil'] = $path;
        }

        // 4. Update data ke database
        $anggota->update($input);

        // Update nama di tabel users agar sinkron
        Auth::user()->update([
            'name' => $request->nama_lengkap
        ]);

        return redirect()->route('profile.edit')->with('success', 'Profil Anda berhasil diperbarui!');
    }

    /**
     * Menyimpan perubahan password (FUNGSI BARU).
     */
    public function updatePassword(Request $request)
    {
        // 1. Validasi input
        // 'confirmed' akan otomatis mencocokkan input 'password' dengan 'password_confirmation'
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi password baru tidak cocok!',
            'password.min'       => 'Password minimal harus 8 karakter!'
        ]);

        $user = Auth::user();

        // 2. Cek apakah password lama yang dimasukkan benar
        if (!Hash::check($request->current_password, $user->password)) {
            // Jika salah, kembalikan ke halaman profil dengan pesan error
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        // 3. Jika benar, update dengan password baru yang di-hash (enkripsi)
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile.edit')->with('success', 'Password berhasil diperbarui!');
    }
}
