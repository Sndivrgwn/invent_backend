<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Location;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Item::all(), 200);
    }

    public function search(Request $request)
    {
        $keyword = $request->query('q');
        $items = Item::where('code', 'LIKE', "%$keyword%")
            ->orWhere('name', 'LIKE', "%$keyword%")
            ->orWhere('brand', 'LIKE', "%$keyword%")
            ->orWhere('type', 'LIKE', "%$keyword%")
            ->orWhere('condition', 'LIKE', "%$keyword%")
            ->limit(10)
            ->get();

        return response()->json($items);
    }

    public function getAll()
    {
        $items = Item::with(['category', 'location'])->get();

        return $items;
    }

    public function getAllItems(Request $request)
{
    $query = Item::with(['category', 'location']);

    if ($request->has('search-navbar') && $request->filled('search-navbar')) {
        $search = $request->input('search-navbar');

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%") // PRODUCT
              ->orWhere('code', 'like', "%{$search}%") // SERIAL NUMBER
              ->orWhere('brand', 'like', "%{$search}%") // BRAND
              ->orWhereHas('category', function ($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%"); // CATEGORY
              })
              ->orWhereHas('location', function ($q) use ($search) {  
                  $q->where('description', 'like', "%{$search}%"); // LOCATION
              })
              ->orWhere('type', 'like', "%{$search}%") // TYPE
              ->orWhere('condition', 'like', "%{$search}%") // CONDITIONAL
              ->orWhere('status', 'like', "%{$search}%"); // STATUS
        });
    }

    $items = $query->paginate(20);
    $locations = Location::all();

    return view('pages.products', compact('items', 'locations'));
}


    public function filter(Request $request)
    {
        $items = Item::with('location')->when($request->brand, fn($q) => $q->where('brand', $request->brand))
            ->when($request->category, fn($q) => $q->whereHas('category', fn($q) => $q->where('name', $request->category)))
            ->when($request->location, fn($q) => $q->whereHas('location', fn($q) => $q->where('description', $request->location)))
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->condition, fn($q) => $q->where('condition', $request->condition))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->get();

        return response()->json($items);
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
        try {
            // Validasi input
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|unique:items,code',
                'brand' => 'required|string',
                'type' => 'required|string',
                'condition' => 'required|string',
                'status' => 'required|in:READY,NOT READY',
                'category_id' => 'required|exists:categories,id',
                'location_id' => 'required|exists:locations,id',
                'description' => 'nullable|string',
            ]);

            // Simpan item
            $item = Item::create($validated);

            return response()->json([
                'message' => 'Item created successfully',
                'data' => $item,
            ], 201);
        } catch (ValidationException $e) {
            // Tangkap error validasi
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            // Tangkap error database
            return response()->json([
                'message' => 'Database error',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            // Tangkap error umum lainnya
            return response()->json([
                'message' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
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
            'code' => 'required|string|unique:items,code' . $id,
            'brand' => 'required|string',
            'type' => 'required|string',
            'condition' => 'required|string',
            'status' => 'required|in:READY,NOT READY',
            'category_id' => 'required|exists:categories,id',
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
