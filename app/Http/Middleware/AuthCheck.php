<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthCheck
{
    /**
     * Cek apakah user login dan rolenya sesuai dengan yang diizinkan.
     * Pemakaian: ->middleware('authcheck:mentor') atau ->middleware('authcheck:mentor,mahasiswa')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Ambil value role, aman baik role berupa enum maupun string biasa
        $userRole = $user->role instanceof \BackedEnum ? $user->role->value : $user->role;

        if (! empty($roles) && ! in_array($userRole, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
