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

        if ($request->ajax()) {
            return response()->json(['data' => $query->get()]);
        }

        $user = $query->get();
        return view('pages.userManagement', compact('user', 'roles'));
    }



    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'roles_id' => 'required|',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);

        return redirect()->route('users')->with('success', 'User created successfully');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles_id' => 'required|',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user,
        ], 200);
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ], 200);
    }
    public function show($id)
{
    try {
        $user = User::with(['roles', 'loans' => function($query) {
            $query->latest()->take(4); // Get only the 4 most recent loans
        }, 'loans.items'])->findOrFail($id);
        
        // Get total counts without limiting
        $totalLoans = $user->loans()->count();
        $totalReturned = $user->loans()->where('status', 'returned')->count();
        
        return response()->json([
            'user' => $user,
            'total_loans' => $totalLoans,
            'total_returned_loans' => $totalReturned,
            'has_more_loans' => $totalLoans > 4, // Flag if there are more loans
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error fetching user details',
            'error' => $e->getMessage()
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
        return response()->json([
            'message' => 'Error fetching loans',
            'error' => $e->getMessage()
        ], 500);
    }
}
}
