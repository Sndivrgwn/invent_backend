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
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    // Cache configuration
    const CACHE_TTL = 3600; // 1 hour
    const CACHE_KEY_PREFIX = 'items_';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cacheKey = self::CACHE_KEY_PREFIX . 'all';
        return response()->json(Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return Item::all();
        }), 200);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls,txt'
        ], [
            'file.required' => 'Silakan pilih file terlebih dahulu.',
            'file.mimes' => 'Format file harus .xlsx, .xls, .csv, atau .txt.',
        ]);

        try {
            $importer = new ProductsImport();
            Excel::import($importer, $request->file('file'));

            if ($importer->importedRows === 0) {
                return back()->with('error', 'Tidak ada data yang berhasil diimpor. Pastikan file tidak kosong.');
            }

            // Clear relevant caches after import
            $this->clearItemsCache();

            return back()->with('success', 'Data produk berhasil diimpor!');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                Log::error('Import error: ' . $e->getMessage());

                preg_match("/Duplicate entry '([^']+)'/", $e->getMessage(), $matches);
                $duplicate = $matches[1] ?? null;

                $message = $duplicate
                    ? "Gagal impor: Item dengan kode \"$duplicate\" sudah ada."
                    : "Gagal impor: Terjadi duplikasi data. Pastikan tidak ada kode produk yang sama.";

                return back()->with('error', $message);
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

    public function downloadTemplateExcel()
    {
        return response()->download(storage_path('app/public/import/template_produk.xlsx'));
    }

    public function search(Request $request)
    {
        $keyword = $request->query('q');
        $cacheKey = self::CACHE_KEY_PREFIX . 'search_' . md5($keyword);

        return response()->json(Cache::remember($cacheKey, self::CACHE_TTL, function () use ($keyword) {
            return Item::where('code', 'LIKE', "%$keyword%")
                ->orWhere('name', 'LIKE', "%$keyword%")
                ->orWhere('brand', 'LIKE', "%$keyword%")
                ->orWhere('type', 'LIKE', "%$keyword%")
                ->orWhere('condition', 'LIKE', "%$keyword%")
                ->limit(10)
                ->get();
        }));
    }

    public function getAll()
    {
        $cacheKey = self::CACHE_KEY_PREFIX . 'all_with_relations';
        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return Item::with(['category', 'location'])->get();
        });
    }

    public function getAllItems(Request $request)
{
    $sortBy = $request->input('sortBy');
    $sortDir = $request->input('sortDir', 'asc');
    $search = $request->input('search-navbar');
    $perPage = 20;

    // Current page
    $currentPage = LengthAwarePaginator::resolveCurrentPage();

    // Generate unique cache key based on request filters (tidak termasuk halaman!)
    $cacheKey = self::CACHE_KEY_PREFIX . 'filtered_items_' . md5(json_encode([
        'sortBy' => $sortBy,
        'sortDir' => $sortDir,
        'search' => $search
    ]));

    // Ambil data hasil filter dan sort dari cache (tanpa pagination)
    $cachedData = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($request, $sortBy, $sortDir, $search) {
        $query = Item::select('items.*')
            ->join('locations', 'items.location_id', '=', 'locations.id')
            ->with(['category', 'location']);

        // Filtering
        if ($search) {
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

        // Sorting
        $allowedSorts = ['name', 'code', 'type', 'condition', 'status', 'rack', 'created_at'];
        if ($sortBy && in_array($sortBy, $allowedSorts)) {
            if ($sortBy === 'rack') {
                $query->orderBy('locations.name', $sortDir);
            } else {
                $query->orderBy("items.$sortBy", $sortDir);
            }
        } else {
            $query->orderBy('items.created_at', 'desc');
        }

        return $query->get(); // Cache hanya data mentah
    });

    // Lakukan pagination manual dari hasil cache
    $paginatedItems = new LengthAwarePaginator(
        collect($cachedData)->forPage($currentPage, $perPage)->values(),
        count($cachedData),
        $perPage,
        $currentPage,
        [
            'path' => request()->url(),
            'query' => request()->query(),
        ]
    );

    return view('pages.products', [
        'items' => $paginatedItems,
        'locations' => Location::all(),
        'categories' => Category::all(),
        'sortBy' => $sortBy,
        'sortDir' => $sortDir
    ]);
}


    public function filter(Request $request)
    {
        $cacheKey = self::CACHE_KEY_PREFIX . 'filter_' . md5(json_encode($request->all()));

        return response()->json(Cache::remember($cacheKey, self::CACHE_TTL, function () use ($request) {
            return Item::with('location')
                ->when($request->brand, fn($q) => $q->where('brand', $request->brand))
                ->when($request->category, fn($q) => $q->whereHas('category', fn($q) => $q->where('name', $request->category)))
                ->when($request->location, fn($q) => $q->whereHas('location', fn($q) => $q->where('name', $request->location)))
                ->when($request->type, fn($q) => $q->where('type', $request->type))
                ->when($request->condition, fn($q) => $q->where('condition', $request->condition))
                ->when($request->status, fn($q) => $q->where('status', $request->status))
                ->get();
        })); // Added closing parenthesis for Cache::remember()
    }

    /**
     * Display the total number of items.
     */
    public function totalItems()
    {
        $cacheKey = self::CACHE_KEY_PREFIX . 'total_count';
        $totalItems = Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return Item::count();
        });

        return view('pages.dashboard', compact('totalItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
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
            $item = Item::create($validated);

            // Clear relevant caches
            $this->clearItemsCache();

            return response()->json([
                'message' => 'Item berhasil dibuat',
                'data' => $item,
            ], 201);
        } catch (ValidationException $e) {
            report($e);
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            report($e);
            return response()->json([
                'message' => 'Kesalahan database',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            report($e);
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
        $cacheKey = self::CACHE_KEY_PREFIX . 'show_' . $id;
        $items = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($id) {
            return Item::with(['category', 'location'])->find($id);
        });

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
                if ($item->image && $item->image !== 'default.png' && Storage::disk('public')->exists($item->image)) {
                    Storage::disk('public')->delete($item->image);
                }
                $path = $request->file('image')->store('items', 'public');
                $validated['image'] = $path;
            } else {
                $validated['image'] = $item->image;
            }

            $item->update($validated);

            // Clear relevant caches
            $this->clearItemsCache();
            $this->clearItemCache($id);

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil diperbarui',
                'data' => $item
            ]);
        } catch (ValidationException $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            report($e);
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

        // Clear relevant caches
        $this->clearItemsCache();
        $this->clearItemCache($id);

        return response()->json(['message' => 'Item berhasil dihapus']);
    }

    /**
     * Clear all items caches
     */
    protected function clearItemsCache()
    {
        $keys = [
            'all',
            'all_with_relations',
            'total_count'
        ];

        foreach ($keys as $key) {
            Cache::forget(self::CACHE_KEY_PREFIX . $key);
        }

        // Also clear all paginated/search/filter caches
        $this->clearItemsListCaches();
    }

    /**
     * Clear specific item cache
     */
    protected function clearItemCache($id)
    {
        Cache::forget(self::CACHE_KEY_PREFIX . 'show_' . $id);
    }

    /**
     * Clear all items list caches (search, filter, paginated)
     */
    protected function clearItemsListCaches()
    {
        // This is a simple implementation that clears all items-related list caches
        // In production, you might want a more targeted approach
        $keys = Cache::getStore()->getRedis()->keys(self::CACHE_KEY_PREFIX . '*');
        foreach ($keys as $key) {
            // Remove prefix from key
            $key = str_replace(config('database.redis.options.prefix'), '', $key);
            if (str_contains($key, 'search_') || str_contains($key, 'filter_') || str_contains($key, 'paginated_')) {
                Cache::forget($key);
            }
        }
    }

    /**
     * Clear all caches when items are modified (callable from other controllers)
     */
    public static function clearAllItemsCache()
    {
        (new self)->clearItemsCache();
        (new self)->clearItemsListCaches();
    }
}
