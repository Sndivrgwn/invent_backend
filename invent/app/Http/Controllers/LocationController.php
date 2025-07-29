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
            ->with(['items.category' => function ($query) {
                $query->select('id', 'name')->distinct();
            }])
            ->get();

        // Process data for view
        $formattedData = $locations->map(function ($location) {
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
                'name' => 'required|string|max:255|unique:locations,name',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'name.required' => 'Nama lokasi wajib diisi.',
                'name.unique' => 'Nama rak sudah digunakan, silakan pilih nama lain.',
                'image.image' => 'File harus berupa gambar.',
                'image.mimes' => 'Gambar harus berformat: jpeg, png, jpg, atau gif.',
                'image.max' => 'Ukuran gambar maksimal 2MB.',
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
                    'message' => 'Lokasi berhasil dibuat',
                    'type' => 'success'
                ]
            ]);
        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            return response()->json([
                'success' => false,
                'toast' => [
                    'message' => 'Gagal membuat lokasi: ' . $e->getMessage(),
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
                        'message' => 'Lokasi tidak ditemukan',
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
                    'message' => 'Data lokasi berhasil diambil',
                    'type' => 'success'
                ]
            ], 200);
        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            return response()->json([
                'toast' => [
                    'message' => 'Gagal mengambil lokasi: ' . $e->getMessage(),
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
                        'message' => 'Lokasi tidak ditemukan',
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
                    'message' => 'Lokasi berhasil diperbarui',
                    'type' => 'success'
                ]
            ]);
        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            return response()->json([
                'success' => false,
                'toast' => [
                    'message' => 'Gagal memperbarui lokasi: ' . $e->getMessage(),
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
                        'message' => 'Lokasi tidak ditemukan',
                        'type' => 'error'
                    ]
                ], 404);
            }

            $location->delete();

            return response()->json([
                'message' => 'Lokasi berhasil dihapus',
                'toast' => [
                    'message' => 'Lokasi berhasil dihapus',
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
