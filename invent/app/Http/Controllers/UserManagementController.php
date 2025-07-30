<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
{
    $query = User::with('roles');
    $roles = Roles::all();

    // Jika admin, filter role
    if (auth()->user()->roles_id == 1) {
        $query->whereIn('roles_id', [1, 2, 4]);
    }

    // Search
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
        });
    }

    // Filter role
    if ($request->filled('role')) {
        $query->whereHas('roles', function ($q) use ($request) {
            $q->where('name', $request->role);
        });
    }

    // Sort
    $sortBy = $request->input('sortBy', 'last_active_at');
    $sortDir = $request->input('sortDir', 'desc');
    $allowedSorts = ['name', 'email', 'last_active_at'];

    if (in_array($sortBy, $allowedSorts)) {
        $query->orderBy($sortBy, $sortDir);
    }

    // Return for AJAX
    if ($request->ajax()) {
        return response()->json(['data' => $query->get()]);
    }

    $user = $query->get();
    return view('pages.userManagement', compact('user', 'roles', 'sortBy', 'sortDir'));
}


    public function store(Request $request)
    {
        try {
            
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

            return redirect()->route('users')->with('toast', [
                'type' => 'success',
                'message' => 'Pengguna berhasil dibuat'
            ]);
            
        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Kesalahan Membuat Pengguna: ' . $e->getMessage()
            ])->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
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

            return response()->json([
                'toast' => [
                    'type' => 'success',
                    'message' => 'Pengguna berhasil diperbarui'
                ],
                'data' => $user,
            ], 200);
            
        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

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
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'toast' => [
                    'type' => 'success',
                    'message' => 'Pengguna berhasil dihapus'
                ]
            ], 200);
            
        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

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
        try {
            $user = User::with(['roles', 'loans' => function($query) {
                $query->latest()->take(4);
            }, 'loans.items'])->findOrFail($id);
            
            $totalLoans = $user->loans()->count();
            $totalReturned = $user->loans()->where('status', 'returned')->count();
            
            return response()->json([
                'user' => $user,
                'total_loans' => $totalLoans,
                'total_returned_loans' => $totalReturned,
                'has_more_loans' => $totalLoans > 4,
            ]);
            
        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

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
        try {
            $loans = Loan::with('items')
                ->where('user_id', $id)
                ->latest()
                ->get();
                
            return response()->json($loans);
            
        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            return response()->json([
                'toast' => [
                    'type' => 'error',
                    'message' => 'Kesalahan mengambil pinjaman: ' . $e->getMessage()
                ]
            ], 500);
        }
    }
}
