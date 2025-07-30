<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Handle API login request
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginapi(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'Informasi login tidak benar',
                    'errors' => [
                        'email' => ['Informasi login yang Anda masukkan tidak valid.']
                    ]
                ], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'message' => 'Login berhasil',
                'user' => $user,
                'token' => $token,
            ], 200);

        } catch (ValidationException $e) {
            report($e); // atau Log::error($e)

            return response()->json([
                'message' => 'Data yang dimasukkan tidak valid',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            report($e); // atau Log::error($e)
            // Log the exception for debugging
            return response()->json([
                'message' => 'gagal Login',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show login form
     * 
     * @return \Illuminate\View\View
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Handle web login request
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
   public function actionlogin(Request $request)
{
    try {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $remember = $request->has('remember');

        if (!Auth::attempt($credentials, $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        session(['last_login_time' => now()]);
        
        // Flash success toast message
        $request->session()->flash('toast', [
            'type' => 'success',
            'message' => 'Login berhasil! selamat kembali!'
        ]);

        return redirect()->intended('dashboard');

    } catch (ValidationException $e) {
        report($e); // atau Log::error($e)

        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput()
            ->with('toast', [
                'type' => 'error',
                'message' => 'Login gagal. check ulang informasi login.'
            ]);
    }
}
}