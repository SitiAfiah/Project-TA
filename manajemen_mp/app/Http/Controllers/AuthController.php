<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Kolat;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Menampilkan Halaman Login
     */
    public function showLogin()
    {
        return view('login-register.login');
    }

    /**
     * Proses Login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Proteksi Status: Jika belum diaktifkan admin, tidak boleh masuk
            if ($user->anggota && $user->anggota->status !== 'Aktif') {
                Auth::logout();
                return back()->with('error', 'Akun Anda sedang dalam tahap verifikasi admin. Mohon tunggu.');
            }

            $request->session()->regenerate();

            // Redirect sesuai role jika diperlukan, atau ke dashboard utama
            return redirect()->intended('/dashboard')->with('success', 'Selamat datang kembali!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Menampilkan Halaman Register
     */
    public function showRegister()
    {
        $data_kolat = Kolat::all();
        return view('login-register.register', compact('data_kolat'));
    }

    /**
     * Proses Register (User & Anggota)
     */
    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'nama_lengkap' => 'required|string|max:255',
            'no_hp' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required|date',
            'alamat' => 'required',
            'kolat_id' => 'required|exists:kolat,id',
            'tingkatan' => 'required',
            'tgl_gabung' => 'required|date',
        ]);

        // Cari ID Role "Anggota"
        $role = Role::where('nama_role', 'Anggota')->first();
        $role_id = $role ? $role->id : 2;

        // Gunakan Transaction agar data User & Anggota sinkron
        DB::beginTransaction();

        try {
            // 2. Buat Akun User
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $role_id,
            ]);

            // 3. Logika Penomoran Induk JB-xxx
            $terakhir = Anggota::orderBy('id', 'desc')->first();
            $nomor_urut = $terakhir ? sprintf("%03d", intval(substr($terakhir->no_induk, 3)) + 1) : "001";
            $no_induk_baru = "JB-" . $nomor_urut;

            // 4. Buat Profil Anggota
            Anggota::create([
                'user_id'       => $user->id,
                'no_induk'      => $no_induk_baru,
                'nama_lengkap'  => $request->nama_lengkap,
                'role_id'       => $role_id,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir'  => $request->tempat_lahir,
                'tgl_lahir'     => $request->tgl_lahir,
                'no_hp'         => $request->no_hp,
                'kolat_id'      => $request->kolat_id,
                'tingkatan'     => $request->tingkatan,
                'tgl_gabung'    => $request->tgl_gabung,
                'alamat'        => $request->alamat,
                'catatan_medis' => $request->catatan_medis,
                'jabatan'       => 'anggota',
                'status'        => 'Non-Aktif', // Default sesuai permintaanmu
            ]);

            DB::commit();

            return redirect()->route('login')->with('success', 'Registrasi Berhasil! Akun Anda akan segera diverifikasi oleh Admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Register Error: " . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem saat mendaftar.');
        }
    }

    /**
     * Proses Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil keluar.');
    }
}
