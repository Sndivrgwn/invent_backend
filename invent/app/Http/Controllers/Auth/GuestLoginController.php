<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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
}
