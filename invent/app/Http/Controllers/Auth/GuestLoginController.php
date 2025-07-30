<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\DeleteGuestAfterDelay;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class GuestLoginController extends Controller
{
    // Login sebagai tamu
    public function login()
    {
        $user = User::create([
            'name' => 'Guest_' . Str::random(5),
            'email' => Str::uuid() . '@guest.local',
            'password' => bcrypt(Str::random(10)),
            'roles_id' => 2,
            'is_guest' => true,
        ]);

        Auth::login($user);

        DeleteGuestAfterDelay::dispatch($user->id)->delay(now()->addHour());

        return redirect('/dashboard')->with('toast', [
            'type' => 'success',
            'message' => 'Berhasil masuk sebagai tamu.'
        ]);
    }

    // Logout dan hapus user jika tamu
    public function destroy(Request $request)
    {
        $user = $request->user();

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user->is_guest) {
            $user->delete();
        }

        return redirect()->route('login')->with('toast', [
            'type' => 'success',
            'message' => 'Anda telah berhasil logout.'
        ]);
    }

    // Hapus semua tamu (untuk admin)
    public function destroyAll()
    {
        $deleted = User::where('is_guest', true)->delete();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => "Berhasil menghapus $deleted akun tamu."
        ]);
    }
}
