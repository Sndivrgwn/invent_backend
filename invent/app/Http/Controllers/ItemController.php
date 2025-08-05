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
    const DEFAULT_CACHE_TTL = 3600; // 1 hour
    const SHORT_CACHE_TTL = 600;   // 10 minutes for frequently changing data
    const CACHE_KEY_PREFIX = 'items_';
    const CACHE_VERSION = 'v1_';   // Cache version for easy invalidation
    const CACHE_TAG = 'items';     // Cache tag for all items-related cache

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cacheKey = $this->generateCacheKey('all');
        
        return response()->json(
            Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () {
                Log::debug('Cache miss for items index');
                return Item::all();
            }), 
            200,
            ['X-Cache-Key' => $cacheKey]
        );
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
            DB::beginTransaction();
            
            $importer = new ProductsImport();
            Excel::import($importer, $request->file('file'));

            if ($importer->importedRows === 0) {
                DB::rollBack();
                return back()->with('error', 'Tidak ada data yang berhasil diimpor. Pastikan file tidak kosong.');
            }

            DB::commit();
            
            // Clear all items cache using tags
            Cache::tags(self::CACHE_TAG)->flush();
            Log::debug('Cleared all items cache after import');

            return back()->with('success', 'Data produk berhasil diimpor!');
        } catch (QueryException $e) {
            DB::rollBack();
            
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
            DB::rollBack();
            report($e);
            return back()->with('error', 'Terjadi kesalahan saat impor: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $keyword = $request->query('q');
        $cacheKey = $this->generateCacheKey('search_' . md5($keyword));

        return response()->json(
            Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () use ($keyword) {
                Log::debug('Cache miss for items search: ' . $keyword);
                return Item::where('code', 'LIKE', "%$keyword%")
                    ->orWhere('name', 'LIKE', "%$keyword%")
                    ->orWhere('brand', 'LIKE', "%$keyword%")
                    ->orWhere('type', 'LIKE', "%$keyword%")
                    ->orWhere('condition', 'LIKE', "%$keyword%")
                    ->limit(10)
                    ->get();
            }),
            200,
            ['X-Cache-Key' => $cacheKey]
        );
    }


    public function downloadTemplate()
    {
        return response()->download(storage_path('app/public/import/template_import_produk.csv'));
    }

    public function downloadTemplateExcel()
    {
        return response()->download(storage_path('app/public/import/template_produk.xlsx'));
    }

    public function getAll()
    {
        $cacheKey = $this->generateCacheKey('all_with_relations');
        
        return Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () {
            Log::debug('Cache miss for items with relations');
            return Item::with(['category', 'location'])->get();
        });
    }

    public function getAllItems(Request $request)
    {
        $sortBy = $request->input('sortBy');
        $sortDir = $request->input('sortDir', 'asc');
        $search = $request->input('search-navbar');
        $perPage = 20;

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $cacheKey = $this->generateCacheKey('filtered_items_' . md5(json_encode([
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
            'search' => $search
        ])));

        $cachedData = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () use ($request, $sortBy, $sortDir, $search) {
            Log::debug('Cache miss for filtered items');
            
            $query = Item::select('items.*')
                ->join('locations', 'items.location_id', '=', 'locations.id')
                ->with(['category', 'location']);

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

            return $query->get();
        });

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
        $cacheKey = $this->generateCacheKey('filter_' . md5(json_encode($request->all())));

        return response()->json(
            Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () use ($request) {
                Log::debug('Cache miss for items filter');
                return Item::with('location')
                    ->when($request->brand, fn($q) => $q->where('brand', $request->brand))
                    ->when($request->category, fn($q) => $q->whereHas('category', fn($q) => $q->where('name', $request->category)))
                    ->when($request->location, fn($q) => $q->whereHas('location', fn($q) => $q->where('name', $request->location)))
                    ->when($request->type, fn($q) => $q->where('type', $request->type))
                    ->when($request->condition, fn($q) => $q->where('condition', $request->condition))
                    ->when($request->status, fn($q) => $q->where('status', $request->status))
                    ->get();
            }),
            200,
            ['X-Cache-Key' => $cacheKey]
        );
    }

    public function totalItems()
    {
        $cacheKey = $this->generateCacheKey('total_count');
        $totalItems = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::SHORT_CACHE_TTL, function () {
            Log::debug('Cache miss for items count');
            return Item::count();
        });

        return view('pages.dashboard', compact('totalItems'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
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

            DB::commit();
            
            // Clear all items cache using tags
            Cache::tags(self::CACHE_TAG)->flush();
            Log::debug('Cleared all items cache after store');

            return response()->json([
                'message' => 'Item berhasil dibuat',
                'data' => $item,
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'message' => 'Kesalahan database',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'message' => 'Kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $cacheKey = $this->generateCacheKey('show_' . $id);
        $items = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () use ($id) {
            Log::debug('Cache miss for item show: ' . $id);
            return Item::with(['category', 'location'])->find($id);
        });

        if (!$items) {
            return response()->json(['message' => 'Item tidak ditemukan'], 404);
        }

        return response()->json([
            'data' => $items
        ], 200, ['X-Cache-Key' => $cacheKey]);
    }

    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();
            
            $item = Item::find($id);
            if (!$item) {
                DB::rollBack();
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
            
            DB::commit();

            // Clear all items cache using tags
            Cache::tags(self::CACHE_TAG)->flush();
            Log::debug('Cleared all items cache after update');

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil diperbarui',
                'data' => $item
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            
            $items = Item::find($id);
            if (!$items) {
                DB::rollBack();
                return response()->json(['message' => 'Item tidak ditemukan'], 404);
            }

            $items->delete();
            
            DB::commit();

            // Clear all items cache using tags
            Cache::tags(self::CACHE_TAG)->flush();
            Log::debug('Cleared all items cache after delete');

            return response()->json(['message' => 'Item berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return response()->json(['message' => 'Gagal menghapus item'], 500);
        }
    }

    /**
     * Generate consistent cache key with version prefix
     */
    protected function generateCacheKey(string $key): string
    {
        return self::CACHE_VERSION . self::CACHE_KEY_PREFIX . $key;
    }

    /**
     * Clear all caches when items are modified (callable from other controllers)
     */
    protected function clearAllItemsCache()
    {
        Cache::tags(self::CACHE_TAG)->flush();
        Log::debug('Cleared all items cache using tags');
    }
}