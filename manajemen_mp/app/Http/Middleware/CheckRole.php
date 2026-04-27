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
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Ambil nama role user yang sedang login (lewat relasi yang kita buat kemarin)
        $userRole = Auth::user()->role->nama_role;

        // 3. Cek apakah role user ada di dalam daftar role yang diperbolehkan?
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // 4. Kalau nggak cocok, lempar ke halaman 403 (Forbidden)
        abort(403, 'Akses Ditolak. Anda tidak memiliki wewenang untuk mengakses halaman ini.');
    }
}
