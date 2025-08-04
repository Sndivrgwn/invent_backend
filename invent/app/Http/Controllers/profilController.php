<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    // Cache configuration
    const CACHE_TTL = 1800; // 30 minutes
    const CACHE_KEY_PREFIX = 'profile_';

    public function index()
    {
        try {
            $user = auth()->user();
            $cacheKey = self::CACHE_KEY_PREFIX . 'stats_' . $user->id;

            $stats = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($user) {
                return [
                    'totalLoans' => $user->loans()->count(),
                    'totalReturns' => $user->loans()->where('status', 'returned')->count()
                ];
            });

            return view('pages.profil', array_merge(
                ['user' => $user],
                $stats
            ));
            
        } catch (\Exception $e) {
            Log::error('Profile index error: ' . $e->getMessage());
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Gagal memuat data profil'
            ]);
        }
    }

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

            $user = auth()->user();
            $user->update(['name' => $request->name]);

            // Clear profile cache
            $this->clearProfileCache($user->id);

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

            // Clear profile cache
            $this->clearProfileCache($user->id);

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

            // Clear profile cache
            $this->clearProfileCache($user->id);

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

    /**
     * Clear profile cache for user
     */
    protected function clearProfileCache($userId)
    {
        Cache::forget(self::CACHE_KEY_PREFIX . 'stats_' . $userId);
    }

    /**
     * Clear all profile caches (callable from other controllers)
     */
    public static function clearAllProfileCaches($userId = null)
    {
        $controller = new self();
        if ($userId) {
            $controller->clearProfileCache($userId);
        } else {
            // Clear all profile caches if no specific user ID provided
            $keys = Cache::getStore()->getRedis()->keys(
                config('database.redis.options.prefix') . self::CACHE_KEY_PREFIX . '*'
            );
            foreach ($keys as $key) {
                $key = str_replace(config('database.redis.options.prefix'), '', $key);
                Cache::forget($key);
            }
        }
    }
}