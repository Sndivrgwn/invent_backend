<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Item::all(), 200);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ], [
            'file.required' => 'Silakan pilih file CSV terlebih dahulu.',
            'file.mimes' => 'Format file harus .csv atau .txt.',
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));

            return back()->with('success', 'Data produk berhasil diimpor!');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                // Ambil kode yang menyebabkan duplikat (jika bisa)
                preg_match("/Duplicate entry '(.+?)'/", $e->getMessage(), $matches);
                $duplicate = $matches[1] ?? 'Tidak diketahui';

                return back()->with('error', "Gagal impor: Item dengan kode \"$duplicate\" sudah ada.");
            }

            report($e);
            return back()->with('error', 'Terjadi kesalahan saat impor.');
        } catch (\Exception $e) {
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat impor: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return response()->download(storage_path('app/public/import/template_import_produk.csv'));
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
        $sortBy = $request->input('sortBy', 'name'); // default
        $sortDir = $request->input('sortDir', 'asc');

        $query = Item::select('items.*')
            ->join('locations', 'items.location_id', '=', 'locations.id')
            ->with(['category', 'location']);

        // Filtering
        if ($request->has('search-navbar') && $request->filled('search-navbar')) {
            $search = $request->input('search-navbar');

            $query->where(function ($q) use ($search) {
                $q->where('items.name', 'like', "%{$search}%")
                    ->orWhere('items.code', 'like', "%{$search}%")
                    ->orWhere('items.brand', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('locations.name', 'like', "%{$search}%")
                    ->orWhere('items.type', 'like', "%{$search}%")
                    ->orWhere('items.condition', 'like', "%{$search}%")
                    ->orWhere('items.status', 'like', "%{$search}%");
            });
        }

        // Sort
        $allowedSorts = ['name', 'code', 'type', 'condition', 'status', 'rack'];
        if (in_array($sortBy, $allowedSorts)) {
            if ($sortBy === 'rack') {
                $query->orderBy('locations.name', $sortDir);
            } else {
                $query->orderBy("items.$sortBy", $sortDir);
            }
        }

        $items = $query->paginate(20)->appends([
            'search-navbar' => $request->input('search-navbar'),
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
        ]);

        $locations = Location::all();
        $categories = Category::all();

        return view('pages.products', compact('items', 'locations', 'categories', 'sortBy', 'sortDir'));
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
                'image' => 'nullable|image|max:2048',
                'status' => 'required|in:READY,NOT READY',
                'category_id' => 'required|exists:categories,id',
                'location_id' => 'required|exists:locations,id',
                'description' => 'nullable|string',
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('items', 'public');
            } else {
                $path = 'default.png';
            }

            $validated['image'] = $path;
            // Simpan item
            $item = Item::create($validated);

            return response()->json([
                'message' => 'Item berhasil dibuat',
                'data' => $item,
            ], 201);
        } catch (ValidationException $e) {
            report($e); // atau Log::error($e)

            // Tangkap error validasi
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            report($e); // atau Log::error($e)

            // Tangkap error database
            return response()->json([
                'message' => 'Kesalahan database',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            report($e); // atau Log::error($e)

            // Tangkap error umum lainnya
            return response()->json([
                'message' => 'Kesalahan server',
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
            return response()->json(['message' => 'Item tidak ditemukan'], 404);
        }

        return response()->json([
            'data' => $items
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $item = Item::find($id);
            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|unique:items,code,' . $id,
                'brand' => 'required|string',
                'type' => 'required|string',
                'condition' => 'required|string',
                'status' => 'required|in:READY,NOT READY',
                'category_id' => 'required|exists:categories,id',
                'location_id' => 'required|exists:locations,id',
                'description' => 'nullable|string',
                'image' => 'nullable|image|max:2048',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if it exists and isn't the default
                if ($item->image && $item->image !== 'default.png' && Storage::disk('public')->exists($item->image)) {
                    Storage::disk('public')->delete($item->image);
                }

                // Store new image
                $path = $request->file('image')->store('items', 'public');
                $validated['image'] = $path;
            } else {
                // Keep the existing image if no new image is uploaded
                $validated['image'] = $item->image;
            }

            $item->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil diperbarui',
                'data' => $item
            ]);
        } catch (ValidationException $e) {
            report($e); // atau Log::error($e)

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            return response()->json([
                'success' => false,
                'message' => 'Kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $items = Item::find($id);
        if (!$items) {
            return response()->json(['message' => 'Item tidak ditemukan'], 404);
        }

        $items->delete();
        return response()->json(['message' => 'Item berhasil dihapus']);
    }
}
