<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $AllLocation = Location::all();

        $totalItemAtLocation = Item::selectRaw('location_id, COUNT(*) as total')
            ->groupBy('location_id')
            ->pluck('total', 'location_id');

        $categoryPerLocation = DB::table('items')
            ->join('categories', 'items.category_id', '=', 'categories.id')
            ->select('items.location_id', 'categories.name as category_name')
            ->distinct()
            ->get()
            ->groupBy('location_id')
            ->map(function ($items) {
                return $items->pluck('category_name')->unique()->values();
            });

        return view('pages.inventory', compact('AllLocation', 'totalItemAtLocation', 'categoryPerLocation'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $location = Location::create($validated);
        return response()->json(['message' => 'Location created successfully', 'data' => $location], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $location = Location::with('items.category')->find($id);

        if (!$location) {
            return response()->json(['message' => 'Location not found'], 404);
        }

        $uniqueCategories = $location->items
            ->pluck('category.name')
            ->filter()
            ->unique()
            ->values();

        return response()->json([
            'location' => $location,
            'items' => $location->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'code' => $item->code,
                    'condition' => $item->condition,
                    'category' => $item->category?->name,
                ];
            }),
            'categories' => $uniqueCategories, // ← ini penting
        ], 200);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $location = Location::find($id);
        if (!$location) {
            return response()->json(['message' => 'Location not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $location->update($validated);
        return response()->json(['message' => 'Location updated successfully', 'data' => $location], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $location = Location::find($id);
        if (!$location) {
            return response()->json(['message' => 'Location not found'], 404);
        }

        $location->delete();
        return response()->json(['message' => 'Location deleted successfully'], 200);
    }
}
