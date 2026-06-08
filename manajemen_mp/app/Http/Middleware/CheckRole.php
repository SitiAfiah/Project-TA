<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // // 1. Pastikan user sudah login
        // if (!Auth::check()) {
        //     return redirect()->route('login');
        // }

        // $user = Auth::user();
        // $namaRole = null;

        // // 2. Ambil nama_role dari relasi.
        // if ($user->role) {
        //     $namaRole = $user->role->nama_role;
        // } elseif ($user->anggota && $user->anggota->role) {
        //     $namaRole = $user->anggota->role->nama_role;
        // }

        // // ==========================================
        // // LETAKKAN KODE DD DI SINI (UNTUK DEBUGGING)
        // // ==========================================
        // // dd('Role di DB: ' . $namaRole, 'Role di Rute: ', $roles);

        // // 3. Cek apakah nama_role user ada di dalam daftar role
        // if (in_array($namaRole, $roles)) {
        //     return $next($request);
        // }

        // // 4. Jika role tidak cocok, tolak akses
        // abort(403, 'Akses Ditolak. Anda tidak memiliki wewenang untuk mengakses halaman ini.');


        // baru
        // 1. Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. LOGIKA BARU: Pengecekan Banyak Peran (Multiple Roles)
        if ($user->anggota) {
            // Looping daftar role yang diizinkan oleh Route (misal: 'Pengurus', 'Pelatih')
            foreach ($roles as $role) {
                // Jika user yang sedang login punya salah satu role tersebut, langsung loloskan!
                if ($user->anggota->roles->contains('nama_role', $role)) {
                    return $next($request);
                }
            }
        }

        // 3. Jika tidak ada satupun role yang cocok, tolak akses
        abort(403, 'Akses Ditolak. Anda tidak memiliki wewenang untuk mengakses halaman ini.');
    }
}
