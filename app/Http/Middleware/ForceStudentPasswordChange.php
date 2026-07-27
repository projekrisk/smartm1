<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ForceStudentPasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Biarkan request Livewire lewat agar tampilan web tidak error
        if ($request->routeIs('livewire.*') || $request->ajax()) {
            return $next($request);
        }

        if ($user && $user->peran === 'siswa') {
            // Jika password masih sama dengan username (Masih berupa NISN)
            if (Hash::check($user->username, $user->password)) {
                
                // Jika sedang berada di halaman ganti password atau halaman logout, IZINKAN
                if ($request->is('siswa/ubah-password') || $request->is('siswa/logout')) {
                    return $next($request);
                }

                // JIKA TIDAK, PAKSA PINDAH KE HALAMAN GANTI PASSWORD
                return redirect('/siswa/ubah-password');
            }
        }

        return $next($request);
    }
}