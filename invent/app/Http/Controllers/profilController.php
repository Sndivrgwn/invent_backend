<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            
            $totalLoans = $user->loans()->count();
            $totalReturns = $user->loans()->where('status', 'returned')->count();

            return view('pages.profil', compact('user', 'totalLoans', 'totalReturns'));
            
        } catch (\Exception $e) {
            Log::error('Profile index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat data profil');
        }
    }
    
    // Change all success returns to use the toast format
public function updateName(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'nameUpdate')
                ->withInput();
        }

        auth()->user()->update(['name' => $request->name]);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Nama berhasil diperbarui'
        ]);
        
    } catch (\Exception $e) {
        Log::error('Name update error: ' . $e->getMessage());
        return redirect()->back()->with('toast', [
            'type' => 'error',
            'message' => 'Gagal memperbarui nama'
        ]);
    }
}

// Do the same for other methods (updateEmail and updateAvatar)
public function updateEmail(Request $request)
{
    try {
        $user = auth()->user();
        
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'emailUpdate')
                ->withInput();
        }

        $user->update(['email' => $request->email]);

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Email berhasil diperbarui'
        ]);
        
    } catch (\Exception $e) {
        Log::error('Email update error: ' . $e->getMessage());
        return redirect()->back()->with('toast', [
            'type' => 'error',
            'message' => 'Gagal memperbarui email'
        ]);
    }
}

public function updateAvatar(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('toast', [
                    'type' => 'error',
                    'message' => $validator->errors()->first()
                ])
                ->withErrors($validator, 'avatarUpdate')
                ->withInput();
        }

        $user = auth()->user();
        $file = $request->file('avatar');

        // Delete old avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store with unique filename
        $filename = 'avatar-' . $user->id . '-' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('avatars', $filename, 'public');

        // Update database
        $user->avatar = $path;
        $user->save();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'Avatar berhasil diperbarui'
        ]);
        
    } catch (\Exception $e) {
        Log::error('Avatar upload error: ' . $e->getMessage());
        return redirect()->back()->with('toast', [
            'type' => 'error',
            'message' => 'Gagal memperbarui Avatar'
        ]);
    }
}
}