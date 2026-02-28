<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureMahasiswaAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('mahasiswa')->check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
