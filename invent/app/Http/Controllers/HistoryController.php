<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class HistoryController extends Controller
{
    // Cache configuration
    const CACHE_TTL = 3600; // 1 hour
    const CACHE_KEY_PREFIX = 'history_';

    public function index(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Generate unique cache key based on request parameters
        $cacheKey = self::CACHE_KEY_PREFIX . md5(json_encode([
            'search' => $search,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'page' => $request->input('page', 1)
        ]));

        $data = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($request, $search, $startDate, $endDate) {
            $loans = Loan::query()->where('status', 'returned')
                ->with(['items.category', 'items.location', 'return'])
                ->orderBy('loan_date', 'desc');

            $locations = Location::all();
            $categories = Category::all();

            if ($search) {
                $loans->where(function ($query) use ($search) {
                    $query->where('code_loans', 'like', "%{$search}%")
                        ->orWhere('loaner_name', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('loan_date', 'like', "%{$search}%")
                        ->orWhere('return_date', 'like', "%{$search}%")
                        ->orWhereHas('items', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                ->orWhere('type', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%")
                                ->orWhereHas('category', function ($query) use ($search) {
                                    $query->where('name', 'like', "%{$search}%");
                                });
                        })
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Date range filter
            if ($startDate && $endDate) {
                $loans->whereBetween('loan_date', [$startDate, $endDate]);
            } elseif ($startDate) {
                $loans->whereDate('loan_date', '>=', $startDate);
            } elseif ($endDate) {
                $loans->whereDate('loan_date', '<=', $endDate);
            }

            return [
                'loans' => $loans->with('items.category')->paginate(20),
                'locations' => $locations,
                'categories' => $categories
            ];
        });

        return view('pages.history', array_merge($data, ['search' => $search]));
    }

    public function filter(Request $request)
    {
        $cacheKey = self::CACHE_KEY_PREFIX . 'filter_' . md5(json_encode($request->all()));

        $loans = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($request) {
            return Loan::with(['items.location', 'items.category', 'return'])
                ->where('status', 'returned')
                ->whereHas('items', function ($query) use ($request) {
                    $query->when($request->brand, fn($q) => $q->where('brand', $request->brand))
                        ->when($request->type, fn($q) => $q->where('type', $request->type))
                        ->when($request->condition, fn($q) => $q->where('condition', $request->condition))
                        ->when($request->category, function ($q) use ($request) {
                            $q->whereHas('category', fn($q) => $q->where('name', $request->category));
                        })
                        ->when($request->location, function ($q) use ($request) {
                            $q->whereHas('location', fn($q) => $q->where('description', $request->location));
                        });
                })
                ->when($request->status, fn($q) => $q->where('status', $request->status))
                ->get()
                ->map(function ($loan) {
                    $loan->encrypted_id = Crypt::encryptString($loan->id);
                    $loan->can_delete = auth()->user()->can('adminFunction');
                    return $loan;
                });
        });

        return response()->json($loans);
    }

    public function show($id): JsonResponse
    {
        $cacheKey = self::CACHE_KEY_PREFIX . 'loan_' . $id;

        try {
            $loan = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($id) {
                return Loan::with([
                    'items.category:id,name',
                    'items.location:id,name',
                    'user:id,name',
                    'items',
                    'return'
                ])->findOrFail($id);
            });

            return response()->json([
                'success' => true,
                'data' => $loan
            ]);
        } catch (\Exception $e) {
            report($e);
            Log::error("Failed to fetch loan {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Pinjaman tidak ditemukan'
            ], 404);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $loan = Loan::findOrFail($id);

            if ($loan->status === 'borrowed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus pinjaman aktif'
                ], 403);
            }

            $loan->delete();

            // Clear relevant caches
            $this->clearLoanCaches($id);
            $this->clearHistoryListCaches();

            return response()->json([
                'success' => true,
                'message' => 'Pinjaman berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            report($e);
            Log::error("Failed to delete loan {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pinjaman'
            ], 500);
        }
    }

    /**
     * Clear all caches related to a specific loan
     */
    protected function clearLoanCaches($id)
    {
        Cache::forget(self::CACHE_KEY_PREFIX . 'loan_' . $id);
    }

    /**
     * Clear all history list caches
     */
    protected function clearHistoryListCaches()
    {
        // This is a simple implementation that clears all history-related caches
        // In production, you might want a more targeted approach
        $keys = Cache::getStore()->getRedis()->keys(self::CACHE_KEY_PREFIX . '*');
        foreach ($keys as $key) {
            // Remove prefix from key
            $key = str_replace(config('database.redis.options.prefix'), '', $key);
            Cache::forget($key);
        }
    }

    /**
     * Clear all caches when loan status changes
     */
    public static function clearLoanRelatedCaches($loanId = null)
    {
        if ($loanId) {
            Cache::forget(self::CACHE_KEY_PREFIX . 'loan_' . $loanId);
        }
        // Also clear all list caches since they might be affected
        (new self)->clearHistoryListCaches();
    }
}