<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {

                // Ambil data user yang sedang login
                $user = Auth::guard($guard)->user();
                $roleName = null;

                // Cek nama role sesuai struktur relasi TapakMP
                if ($user->role) {
                    $roleName = $user->role->nama_role;
                } elseif ($user->anggota && $user->anggota->role) {
                    $roleName = $user->anggota->role->nama_role;
                }

                // Arahkan ke dashboard yang sesuai
                if ($roleName === 'Anggota') {
                    return redirect()->route('anggota.dashboard');
                }

                if ($roleName === 'Pelatih' || $roleName === 'Pengurus') {
                    return redirect()->route('dashboard');
                }

                // Default bawaan Laravel jika role tidak terdefinisi
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
