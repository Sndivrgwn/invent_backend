<?php

namespace App\Http\Controllers;

use App\Exports\CategoryExport;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Exception;

class AnalyticsController extends Controller
{
    // Cache configuration
    const DEFAULT_CACHE_TTL = 3600; // 1 hour
    const CACHE_KEY_PREFIX = 'analytics_';
    const CACHE_VERSION = 'v1_';   // Cache version for easy invalidation
    const CACHE_TAG = 'analytics'; // Cache tag for all analytics-related cache

    /**
     * Display analytics dashboard
     */
    public function index()
    {
        try {
            $cacheKey = $this->generateCacheKey('categories_data');
            
            // Try to get data from cache
            $categories = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () {
                Log::debug('Cache miss for analytics categories data');
                return $this->getCategoriesData();
            });

            return view('pages.analytics', compact('categories'));
        } catch (Exception $e) {
            Log::error('Error in AnalyticsController@index: ' . $e->getMessage());
            report($e);

            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Gagal memuat data analitik. Tolong coba lagi.'
            ]);
        }
    }

    /**
     * Get categories data (uncached)
     */
    protected function getCategoriesData()
    {
        return Category::with(['items.loans'])->get()
            ->map(function ($category) {
                // Ambil hanya item dengan kondisi GOOD
                $goodItems = $category->items->filter(function ($item) {
                    return $item->condition === 'GOOD';
                })->unique('id');

                $itemsCount = $goodItems->count();

                // Hitung loans hanya dari item kondisi GOOD
                $loanCount = $goodItems->sum(function ($item) {
                    return $item->loans->where('status', 'borrowed')->count();
                });

                $availableCount = $itemsCount - $loanCount;

                // Group berdasarkan type item GOOD
                $allTypes = $category->items->pluck('type')->unique();

                $typeSummaries = $allTypes->map(function ($type) use ($goodItems) {
                    $itemsOfType = $goodItems->where('type', $type);

                    $total = $itemsOfType->count();
                    $loaned = $itemsOfType->sum(function ($item) {
                        return $item->loans->where('status', 'borrowed')->count();
                    });
                    $available = $total - $loaned;

                    return (object)[
                        'type' => $type,
                        'quantity' => $total,
                        'available' => $available,
                        'loaned' => $loaned,
                        'low_stock' => $available < 3 ? 'Yes' : 'No',
                    ];
                });

                $category->items_count = $itemsCount;
                $category->loan_count = $loanCount;
                $category->available_count = $availableCount;
                $category->low_stock = $availableCount < 3 ? 'Yes' : 'No';
                $category->type_summaries = $typeSummaries->values();

                return $category;
            });
    }

    /**
     * Export categories report
     */
    public function export()
    {
        try {
            // Clear cache before export to ensure fresh data
            $this->clearAnalyticsCache();
            
            return Excel::download(new CategoryExport, 'categories_report_' . now()->format('Ymd_His') . '.xlsx');
        } catch (Exception $e) {
            Log::error('Error in AnalyticsController@export: ' . $e->getMessage());
            report($e);

            return redirect()->back()->with('error', 'Gagal menghasilkan ekspor.Tolong coba lagi.');
        }
    }

    /**
     * Store a new category
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

            Category::create($validated);
            
            DB::commit();

            // Clear analytics cache after creating a new category
            $this->clearAnalyticsCache();

            return redirect()->route('analytics')->with('toast', [
                'type' => 'success',
                'message' => 'Kategori berhasil dibuat!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            report($e);
            Log::error('Validation error in AnalyticsController@store: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('toast', [
                    'type' => 'error',
                    'message' => $e->getMessage(),
                ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in AnalyticsController@store: ' . $e->getMessage());
            report($e);

            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Gagal membuat kategori.Tolong coba lagi.'
            ]);
        }
    }

    /**
     * Delete a category and its related items
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $category = Category::findOrFail($id);

            $category->items()->each(function ($item) {
                $item->loans()->detach();
                $item->delete();
            });
            $category->delete();
            
            DB::commit();

            // Clear analytics cache after deletion
            $this->clearAnalyticsCache();

            return response()->json([
                'success' => true,
                'toast' => [
                    'type' => 'success',
                    'message' => 'Kategori dan semua item terkait berhasil dihapus!'
                ],
                'reload' => true
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in AnalyticsController@destroy: ' . $e->getMessage());
            report($e);
            return response()->json([
                'success' => false,
                'toast' => [
                    'type' => 'error',
                    'message' => 'Gagal Menghapus Kategori'
                ]
            ], 500);
        }
    }

    /**
     * Update a category
     */
    public function update(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $id,
                'description' => 'nullable|string|max:500',
            ]);

            $category = Category::findOrFail($id);
            $category->update($validated);
            
            DB::commit();

            // Clear analytics cache after update
            $this->clearAnalyticsCache();

            return response()->json([
                'success' => true,
                'toast' => [
                    'type' => 'success',
                    'message' => 'Kategori berhasil diperbarui!'
                ],
                'reload' => true
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'toast' => [
                    'type' => 'error',
                    'message' => 'Validasi gagal: ' . implode(' ', $e->validator->errors()->all())
                ]
            ], 422);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in AnalyticsController@update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'toast' => [
                    'type' => 'error',
                    'message' => 'Gagal memperbarui kategori'
                ]
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
     * Clear all analytics cache
     */
    protected function clearAnalyticsCache()
    {
        Cache::tags(self::CACHE_TAG)->flush();
        Log::debug('Cleared all analytics cache');
    }
}