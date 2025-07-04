<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Load all locations with counts and categories in single query
        $locations = Location::withCount('items')
            ->with(['items.category' => function($query) {
                $query->select('id', 'name')->distinct();
            }])
            ->get();

        // Process data for view
        $formattedData = $locations->map(function($location) {
            return [
                'location' => $location,
                'total_items' => $location->items_count,
                'categories' => $location->items->pluck('category.name')->filter()->unique()->values()
            ];
        });

        return view('pages.inventory', ['locations' => $formattedData]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $location = new Location();
            $location->name = $validated['name'];
            $location->description = $validated['description'];

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('location_images', 'public');
                $location->image = $imagePath;
            } else {
                $location->image = 'default.png';
            }

            $location->save();

            return response()->json([
                'success' => true,
                'reload' => true, // This will trigger page reload
                'toast' => [
                    'message' => 'Location created successfully',
                    'type' => 'success'
                ]
            ]);

        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            return response()->json([
                'success' => false,
                'toast' => [
                    'message' => 'Failed to create location: ' . $e->getMessage(),
                    'type' => 'error'
                ]
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $location = Location::with('items.category')->find($id);

            if (!$location) {
                return response()->json([
                    'toast' => [
                        'message' => 'Location not found',
                        'type' => 'error'
                    ]
                ], 404);
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
                'categories' => $uniqueCategories,
                'toast' => [
                    'message' => 'Location data retrieved successfully',
                    'type' => 'success'
                ]
            ], 200);

        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            return response()->json([
                'toast' => [
                    'message' => 'Failed to retrieve location: ' . $e->getMessage(),
                    'type' => 'error'
                ]
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, string $id)
    {
        try {
            $location = Location::find($id);
            if (!$location) {
                return response()->json([
                    'success' => false,
                    'toast' => [
                        'message' => 'Location not found',
                        'type' => 'error'
                    ]
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $location->name = $validated['name'];
            $location->description = $validated['description'];

            if ($request->hasFile('image')) {
                if ($location->image !== 'default.png') {
                    Storage::disk('public')->delete($location->image);
                }
                $imagePath = $request->file('image')->store('location_images', 'public');
                $location->image = $imagePath;
            }

            $location->save();

            return response()->json([
                'success' => true,
                'reload' => true,
                'toast' => [
                    'message' => 'Location updated successfully',
                    'type' => 'success'
                ]
            ]);

        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            return response()->json([
                'success' => false,
                'toast' => [
                    'message' => 'Failed to update location: ' . $e->getMessage(),
                    'type' => 'error'
                ]
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $location = Location::find($id);
            if (!$location) {
                return response()->json([
                    'toast' => [
                        'message' => 'Location not found',
                        'type' => 'error'
                    ]
                ], 404);
            }

            $location->delete();

            return response()->json([
                'message' => 'Location deleted successfully',
                'toast' => [
                    'message' => 'Location deleted successfully',
                    'type' => 'success'
                ]
            ], 200);

        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            return response()->json([
                'toast' => [
                    'message' => 'Failed to delete location: ' . $e->getMessage(),
                    'type' => 'error'
                ]
            ], 500);
        }
    }
}