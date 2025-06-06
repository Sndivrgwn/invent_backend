<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutoLogoutAfterTwoDays
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Ambil waktu login terakhir dari session
            $lastLogin = session('last_login_time');

            if (!$lastLogin) {
                // Jika belum ada, simpan sekarang sebagai waktu login
                session(['last_login_time' => now()]);
            } else {
                // Pastikan $lastLogin adalah objek Carbon
                if (! $lastLogin instanceof \Illuminate\Support\Carbon) {
                    $lastLogin = \Illuminate\Support\Carbon::parse($lastLogin);
                }

                // Set timezone WIB untuk konsistensi
                $lastLoginWIB = $lastLogin->copy()->setTimezone('Asia/Jakarta');
                $nowWIB = now()->setTimezone('Asia/Jakarta');

                // Hitung selisih menit dari waktu login terakhir
                $elapsed = $nowWIB->diffInMinutes($lastLoginWIB);

                // Jika sudah lebih dari 2 hari (2880 menit), logout
                if ($elapsed > 2880) {
                    Auth::logout();
                    $request->session()->flush();
                    return redirect('/login')->withErrors([
                        'email' => 'Session expired. Please log in again.',
                    ]);
                }
            }
        }

        return $next($request);
    }
}
