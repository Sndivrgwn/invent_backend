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
    return view('pages.userManagement', compact('user' , 'roles'));
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
}
