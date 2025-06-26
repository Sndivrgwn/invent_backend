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
            // Explicitly use the 'web' guard as configured in auth.php
            $guard = Auth::guard('web');
            
            // Perform logout
            $guard->logout();

            // Invalidate the session
            $request->session()->invalidate();

            // Regenerate CSRF token
            $request->session()->regenerateToken();

            // Redirect with success message
            return redirect()->route('login')->with('toast', [
                'type' => 'success',
                'message' => 'You have been logged out successfully.'
            ]);

        } catch (\Exception $e) {
            report($e); // Log the error
            
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Logout failed. Please try again.'
            ]);
        }
    }
}