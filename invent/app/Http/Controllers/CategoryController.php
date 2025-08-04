<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    // Cache configuration
    const CACHE_TTL = 3600; // 1 hour
    const ALL_CATEGORIES_KEY = 'categories_all';
    const CATEGORY_KEY_PREFIX = 'category_';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Cache::remember(self::ALL_CATEGORIES_KEY, self::CACHE_TTL, function () {
            return Category::all();
        });

        return response()->json($categories, 200);
    }

    public function getAllItems()
    {
        $categories = Cache::remember(self::ALL_CATEGORIES_KEY . '_items', self::CACHE_TTL, function () {
            return Category::all();
        });

        return response()->json(['data' => $categories], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama lokasi wajib diisi.',
            'name.unique' => 'Nama lokasi sudah digunakan, silakan pilih nama lain.',
        ]);

        $category = Category::create($validated);
        
        // Clear relevant caches
        $this->clearCategoryCaches();
        
        return response()->json([
            'message' => 'Kategori berhasil dibuat', 
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cacheKey = self::CATEGORY_KEY_PREFIX . $id;
        
        $category = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($id) {
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
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);
        
        // Clear relevant caches
        $this->clearCategoryCaches($id);
        
        return response()->json([
            'message' => 'Kategori berhasil diperbarui', 
            'data' => $category
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $category->delete();
        
        // Clear relevant caches
        $this->clearCategoryCaches($id);
        
        return response()->json(['message' => 'Kategori berhasil dihapus'], 200);
    }

    /**
     * Clear all relevant caches
     */
    protected function clearCategoryCaches($id = null)
    {
        // Clear the all-categories cache
        Cache::forget(self::ALL_CATEGORIES_KEY);
        Cache::forget(self::ALL_CATEGORIES_KEY . '_items');
        
        // Clear specific category cache if ID provided
        if ($id) {
            Cache::forget(self::CATEGORY_KEY_PREFIX . $id);
        }
    }
}