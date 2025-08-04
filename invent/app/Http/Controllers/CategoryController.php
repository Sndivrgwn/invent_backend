<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    // Cache configuration
    const DEFAULT_CACHE_TTL = 3600; // 1 hour
    const CACHE_KEY_PREFIX = 'categories_';
    const CACHE_VERSION = 'v1_';   // Cache version for easy invalidation
    const CACHE_TAG = 'categories'; // Cache tag for all categories-related cache

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cacheKey = $this->generateCacheKey('all');
        
        $categories = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () {
            Log::debug('Cache miss for all categories');
            return Category::all();
        });

        return response()->json($categories, 200);
    }

    public function getAllItems()
    {
        $cacheKey = $this->generateCacheKey('all_with_items');
        
        $categories = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () {
            Log::debug('Cache miss for all categories with items');
            return Category::all();
        });

        return response()->json(['data' => $categories], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'nullable|string',
            ], [
                'name.required' => 'Nama lokasi wajib diisi.',
                'name.unique' => 'Nama lokasi sudah digunakan, silakan pilih nama lain.',
            ]);

            $category = Category::create($validated);
            
            DB::commit();

            // Clear all categories cache using tags
            $this->clearAllCategoriesCache();
            
            return response()->json([
                'message' => 'Kategori berhasil dibuat', 
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating category: ' . $e->getMessage());
            report($e);
            
            return response()->json([
                'message' => 'Gagal membuat kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cacheKey = $this->generateCacheKey('show_' . $id);
        
        $category = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () use ($id) {
            Log::debug('Cache miss for category: ' . $id);
            return Category::find($id);
        });

        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        return response()->json($category, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $category = Category::find($id);
            if (!$category) {
                DB::rollBack();
                return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $id,
                'description' => 'nullable|string',
            ]);

            $category->update($validated);
            
            DB::commit();

            // Clear all categories cache using tags
            $this->clearAllCategoriesCache();
            
            return response()->json([
                'message' => 'Kategori berhasil diperbarui', 
                'data' => $category
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating category: ' . $e->getMessage());
            report($e);
            
            return response()->json([
                'message' => 'Gagal memperbarui kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $category = Category::find($id);
            if (!$category) {
                DB::rollBack();
                return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
            }

            $category->delete();
            
            DB::commit();

            // Clear all categories cache using tags
            $this->clearAllCategoriesCache();
            
            return response()->json(['message' => 'Kategori berhasil dihapus'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting category: ' . $e->getMessage());
            report($e);
            
            return response()->json([
                'message' => 'Gagal menghapus kategori',
                'error' => $e->getMessage()
            ], 500);
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
     * Clear all categories cache using tags
     */
    protected function clearAllCategoriesCache()
    {
        Cache::tags(self::CACHE_TAG)->flush();
        Log::debug('Cleared all categories cache using tags');
    }

    /**
     * Clear all caches when categories are modified (callable from other controllers)
     */
    public static function clearAllCategoriesCacheStatic()
    {
        Cache::tags(self::CACHE_TAG)->flush();
        Log::debug('Cleared all categories cache using static method');
    }
}