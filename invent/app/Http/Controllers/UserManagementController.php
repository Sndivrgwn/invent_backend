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
        $query->whereIn('roles_id', [1, 2]);
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
                'message' => 'You are not allowed to create a SuperAdmin user.'
            ])->withInput();
        }

            $validated['password'] = bcrypt($validated['password']);
            User::create($validated);

            return redirect()->route('users')->with('toast', [
                'type' => 'success',
                'message' => 'User created successfully'
            ]);
            
        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Error creating user: ' . $e->getMessage()
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
                    'message' => 'User updated successfully'
                ],
                'data' => $user,
            ], 200);
            
        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            return response()->json([
                'toast' => [
                    'type' => 'error',
                    'message' => 'Error updating user: ' . $e->getMessage()
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
                    'message' => 'User deleted successfully'
                ]
            ], 200);
            
        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            return response()->json([
                'toast' => [
                    'type' => 'error',
                    'message' => 'Error deleting user: ' . $e->getMessage()
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
                    'message' => 'Error fetching user details: ' . $e->getMessage()
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
                    'message' => 'Error fetching loans: ' . $e->getMessage()
                ]
            ], 500);
        }
    }
}
