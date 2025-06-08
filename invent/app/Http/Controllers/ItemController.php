<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Item::all(), 200);
    }

    public function getAllItems()
    {
        $items = Item::with(['category', 'location'])->paginate(5);
        return view('pages.products', compact('items'));
    }

    /**
     * Display the total number of items.
     */
    public function totalItems() 
    {
        $all = Item::all();
        $totalItems = $all->count();
        return view('pages.dashboard', compact('totalItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:items',
            'status' => 'required|string',
            'category_id' => 'required|exists:categories,id' ,
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|string',
            'location_id' => 'required|exists:locations,id',
            'description' => 'nullable|string',
        ]);

        $items = Item::create($validated);
        return response()->json(['message' => 'Item created successfully', 'data' => $items], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $items = Item::with(['category', 'location'])->find($id);
        if (!$items) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        return response()->json($items);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $items = Item::find($id);
        if (!$items) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:items,code,' . $id,
            'status' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:1',
            'condition' => 'required|string',
            'location_id' => 'required|exists:locations,id',
            'description' => 'nullable|string',
        ]);

        $items->update($validated);
        return response()->json(['message' => 'Item updated successflly', 'data' => $items]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $items = Item::find($id);
        if (!$items) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $items->delete();
        return response()->json(['message' => 'Item deleted successfully']);
    }
}
