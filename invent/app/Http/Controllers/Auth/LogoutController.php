<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        try {
            // Ambil user SEBELUM logout
            $user = $request->user();

            // Logout dengan guard 'web'
            Auth::guard('web')->logout();

            // Invalidate session dan token
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Jika user adalah guest, hapus akunnya
            if ($user && $user->is_guest) {
                $user->delete();
            }

            return redirect()->route('login')->with('toast', [
                'type' => 'success',
                'message' => 'Anda telah berhasil logout.'
            ]);
        } catch (\Exception $e) {
            report($e); // Log error jika ada

            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Logout gagal. Tolong coba lagi.'
            ]);
        }
    }
}
