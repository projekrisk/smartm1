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

        if ($request->routeIs('livewire.*') || $request->ajax()) {
            return $next($request);
        }

        if ($user && $user->peran === 'siswa') {
            if (Hash::check($user->username, $user->password) && !session()->has('skip_password_change')) {
                
                if ($request->is('siswa/ubah-password') || $request->is('siswa/logout')) {
                    return $next($request);
                }

                return redirect('/siswa/ubah-password');
            }
        }

        return $next($request);
    }
}