<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class GuestLoginController extends Controller
{
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

        return redirect('/dashboard')->with('status', 'Berhasil masuk sebagai tamu.');
    }

    public function destroyAll()
    {
        $deleted = User::where('is_guest', true)->delete();

        return redirect()->back()->with('status', "Berhasil menghapus $deleted akun tamu.");
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Jika tamu, hapus akunnya
        if ($user->is_guest) {
            $user->delete();
        }

        return redirect('/login');
    }
}
