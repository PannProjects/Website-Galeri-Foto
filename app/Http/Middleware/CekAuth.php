<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware CekAuth
 * Memeriksa apakah pengguna sudah login sebelum mengakses rute yang dilindungi.
 * Jika belum login, pengguna akan diarahkan ke halaman login.
 */
class CekAuth
{
    /**
     * Tangani request yang masuk
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah sesi user_id ada (tanda sudah login)
        if (!$request->session()->has('user_id')) {
            return redirect()->route('login')
                ->with('peringatan', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
