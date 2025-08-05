<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    // Cache configuration
    const DEFAULT_CACHE_TTL = 3600; // 1 hour
    const CACHE_KEY_PREFIX = 'users_';
    const CACHE_VERSION = 'v1_';
    const CACHE_TAG = 'users';

    public function index(Request $request)
    {
        $cacheKey = $this->generateCacheKey('index_' . md5(json_encode([
            'search' => $request->search,
            'role' => $request->role,
            'sortBy' => $request->sortBy,
            'sortDir' => $request->sortDir,
            'page' => $request->page,
            'auth_role' => auth()->user()->roles_id
        ])));

        $data = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () use ($request) {
            Log::debug('Cache miss for users index');
            $query = User::with('roles');
            $roles = Roles::all();

            if (auth()->user()->roles_id == 1) {
                $query->whereIn('roles_id', [1, 2, 4]);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
                });
            }

            if ($request->filled('role')) {
                $query->whereHas('roles', function ($q) use ($request) {
                    $q->where('name', $request->role);
                });
            }

            $sortBy = $request->input('sortBy', 'last_active_at');
            $sortDir = $request->input('sortDir', 'desc');
            $allowedSorts = ['name', 'email', 'last_active_at'];

            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortDir);
            }

            return [
                'users' => $query->get(),
                'roles' => $roles,
                'sortBy' => $sortBy,
                'sortDir' => $sortDir
            ];
        });

        if ($request->ajax()) {
            return response()->json(['data' => $data['users']]);
        }

        return view('pages.userManagement', $data);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'roles_id' => 'required|exists:roles,id',
            ]);

            if ((int)$validated['roles_id'] === 3) {
                return redirect()->back()->with('toast', [
                    'type' => 'error',
                    'message' => 'Anda tidak diizinkan membuat pengguna superadmin.'
                ])->withInput();
            }

            $validated['password'] = bcrypt($validated['password']);
            User::create($validated);

            DB::commit();

            Cache::tags(self::CACHE_TAG)->flush();
            Log::debug('Cleared all users cache after store');

            return redirect()->route('users')->with('toast', [
                'type' => 'success',
                'message' => 'Pengguna berhasil dibuat'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Kesalahan Membuat Pengguna: ' . $e->getMessage()
            ])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|confirmed',
                'roles_id' => 'required|exists:roles,id',
            ]);

            if ($request->filled('password')) {
                $validated['password'] = bcrypt($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            DB::commit();

            Cache::tags(self::CACHE_TAG)->flush();
            Log::debug('Cleared all users cache after update');

            return response()->json([
                'toast' => [
                    'type' => 'success',
                    'message' => 'Pengguna berhasil diperbarui'
                ],
                'data' => $user,
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'toast' => [
                    'type' => 'error',
                    'message' => 'Kesalahan memperbarui pengguna: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->delete();

            DB::commit();

            Cache::tags(self::CACHE_TAG)->flush();
            Log::debug('Cleared all users cache after delete');

            return response()->json([
                'toast' => [
                    'type' => 'success',
                    'message' => 'Pengguna berhasil dihapus'
                ]
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'toast' => [
                    'type' => 'error',
                    'message' => 'Kesalahan Menghapus Pengguna: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    public function show($id)
    {
        $cacheKey = $this->generateCacheKey('show_' . $id);
        
        try {
            $data = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () use ($id) {
                Log::debug('Cache miss for user show: ' . $id);
                $user = User::with(['roles', 'loans' => function($query) {
                    $query->latest()->take(4);
                }, 'loans.items'])->findOrFail($id);
                
                return [
                    'user' => $user,
                    'total_loans' => $user->loans()->count(),
                    'total_returned_loans' => $user->loans()->where('status', 'returned')->count(),
                    'has_more_loans' => $user->loans()->count() > 4,
                ];
            });

            return response()->json($data);
            
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'toast' => [
                    'type' => 'error',
                    'message' => 'Kesalahan mengambil detail pengguna: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    public function userLoans($id)
    {
        $cacheKey = $this->generateCacheKey('loans_' . $id);
        
        try {
            $loans = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () use ($id) {
                Log::debug('Cache miss for user loans: ' . $id);
                return Loan::with('items')
                    ->where('user_id', $id)
                    ->latest()
                    ->get();
            });
                
            return response()->json($loans);
            
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'toast' => [
                    'type' => 'error',
                    'message' => 'Kesalahan mengambil pinjaman: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    protected function generateCacheKey(string $key): string
    {
        return self::CACHE_VERSION . self::CACHE_KEY_PREFIX . $key;
    }

    public static function clearAllUsersCache()
    {
        Cache::tags(self::CACHE_TAG)->flush();
        Log::debug('Cleared all users cache using static method');
    }
}