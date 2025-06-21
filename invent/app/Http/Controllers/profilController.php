<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Returns; // pastikan ini sesuai
use App\Models\Loan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalLoans = $user->loans()->count();
        $totalReturns = $user->loans()->where('status', 'returned')->count();

        return view('pages.profil', compact('user', 'totalLoans', 'totalReturns'));
    }
    
    public function updateName(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    auth()->user()->update(['name' => $request->name]);

    return redirect()->back()->with('success', 'Name updated successfully');
}

public function updateEmail(Request $request)
{
    $request->validate([
        'email' => 'required|string|email|max:255|unique:users,email,'.auth()->id(),
    ]);

    auth()->user()->update(['email' => $request->email]);

    return redirect()->back()->with('success', 'Email updated successfully');
}


public function updateAvatar(Request $request)
{
    // Add debug logging
    Log::info('Avatar upload attempt started');
    Log::info('Request has file: ' . ($request->hasFile('avatar') ? 'true' : 'false'));
    
    $request->validate([
        'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ]);

    try {
        if ($request->hasFile('avatar')) {
            $user = auth()->user();
            $file = $request->file('avatar');
            
            Log::info('Original filename: ' . $file->getClientOriginalName());
            Log::info('File size: ' . $file->getSize());
            Log::info('File MIME type: ' . $file->getMimeType());

            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store with unique filename
            $filename = 'avatar-' . $user->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('avatars', $filename, 'public');
            
            Log::info('Stored at path: ' . $path);

            // Update database
            $user->avatar = $path;
            $user->save();

            Log::info('Avatar updated successfully');
            return redirect()->back()->with('success', 'Avatar updated successfully');
        }
    } catch (\Exception $e) {
        Log::error('Avatar upload failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Avatar upload failed: ' . $e->getMessage());
    }

    return redirect()->back()->with('error', 'No avatar file provided');
}
}