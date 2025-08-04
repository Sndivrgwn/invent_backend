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
    const DEFAULT_CACHE_TTL = 1800; // 30 minutes
    const CACHE_KEY_PREFIX = 'profile_';
    const CACHE_VERSION = 'v1_';
    const CACHE_TAG = 'profile';

    public function index()
    {
        try {
            $user = auth()->user();
            $cacheKey = $this->generateCacheKey('stats_' . $user->id);

            $stats = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () use ($user) {
                Log::debug('Cache miss for profile stats: ' . $user->id);
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

            Cache::tags(self::CACHE_TAG)->forget($this->generateCacheKey('stats_' . $user->id));
            Log::debug('Cleared profile cache after name update');

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

            Cache::tags(self::CACHE_TAG)->forget($this->generateCacheKey('stats_' . $user->id));
            Log::debug('Cleared profile cache after email update');

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

            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $filename = 'avatar-' . $user->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('avatars', $filename, 'public');

            $user->avatar = $path;
            $user->save();

            Cache::tags(self::CACHE_TAG)->forget($this->generateCacheKey('stats_' . $user->id));
            Log::debug('Cleared profile cache after avatar update');

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

    protected function generateCacheKey(string $key): string
    {
        return self::CACHE_VERSION . self::CACHE_KEY_PREFIX . $key;
    }

    public static function clearAllProfileCaches($userId = null)
    {
        if ($userId) {
            Cache::tags(self::CACHE_TAG)->forget(self::CACHE_VERSION . self::CACHE_KEY_PREFIX . 'stats_' . $userId);
        } else {
            Cache::tags(self::CACHE_TAG)->flush();
        }
        Log::debug('Cleared profile cache for user: ' . ($userId ?? 'all'));
    }
}