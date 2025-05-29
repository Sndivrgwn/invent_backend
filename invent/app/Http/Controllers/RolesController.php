<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Roles;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Roles::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:roles|max:255']);
        $roles = Roles::create(['name' => $request->name]);
        return response()->json(['message' => 'Roles Created', 'roles' => $roles], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $roles = Roles::find($id);
        if (!$roles){
            return response()->json(['message' => 'Roles not found'], 404);
        } 
        return response()->json($roles, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $roles = Roles::find($id);
        if (!$roles) {
            return response()->json(['message' => 'Roles not found'], 404);
        }
        $request->validate(['name' => 'required|string|unique:roles,name,' . $id]);
        $roles->update(['name' => $request->name]);
        return response()->json(['message' => 'Roles updated', 'role' => $roles], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $roles = Roles::find($id);
        if (!$roles) {
            return response()->json(['message' => 'Roles not found'], 404);
        }
        $roles->delete();
        return response()->json(['message' => 'Roles deleted'], 200);
    }
}
