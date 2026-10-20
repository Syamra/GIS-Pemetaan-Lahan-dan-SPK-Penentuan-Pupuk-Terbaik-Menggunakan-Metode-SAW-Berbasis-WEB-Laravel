<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Cek apakah role user saat ini cocok dengan role yang diizinkan di routes
        if (in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }

        // Jika role tidak sesuai, tampilkan halaman error 403 (Akses Ditolak)
        abort(403, 'Akses Ditolak! Anda tidak memiliki izin untuk membuka halaman ini.');
    }
}