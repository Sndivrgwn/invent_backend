<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Returns;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ManageLoanController extends Controller
{
    // Cache configuration
    const CACHE_TTL = 1800; // 30 minutes
    const CACHE_KEY_PREFIX = 'manage_loans_';

    public function index()
    {
        try {
            $search = request('search-navbar');
            $sortBy = request('sortBy', 'loan_date');
            $sortDir = request('sortDir', 'desc');
            $allowedSorts = ['loan_date', 'code_loans', 'loaner_name', 'return_date'];

            // Generate unique cache key
            $cacheKey = self::CACHE_KEY_PREFIX . 'index_' . auth()->id() . '_' . md5(json_encode([
                'search' => $search,
                'sortBy' => $sortBy,
                'sortDir' => $sortDir,
                'page' => request('page', 1)
            ]));

            $myloans = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($search, $sortBy, $sortDir, $allowedSorts) {
                $query = auth()->user()->loans()
                    ->where('status', 'borrowed')
                    ->when($search, function ($query) use ($search) {
                        $query->where(function ($q) use ($search) {
                            $q->where('code_loans', 'like', '%' . $search . '%')
                                ->orWhere('loaner_name', 'like', '%' . $search . '%')
                                ->orWhereHas('items', function ($itemQuery) use ($search) {
                                    $itemQuery->where('code', 'like', '%' . $search . '%')
                                        ->orWhere('name', 'like', '%' . $search . '%');
                                });
                        });
                    })
                    ->with('items');

                if (in_array($sortBy, $allowedSorts)) {
                    $query->orderBy($sortBy, $sortDir);
                } else {
                    $query->orderBy('loan_date', 'desc');
                }

                return $query->paginate(20);
            });

            // Add pagination parameters
            $myloans->appends([
                'search-navbar' => $search,
                'sortBy' => $sortBy,
                'sortDir' => $sortDir,
            ]);

            return view('pages.manageLoan', [
                'myloans' => $myloans,
                'sortBy' => $sortBy,
                'sortDir' => $sortDir
            ]);
        } catch (\Exception $e) {
            report($e);
            Log::error("Failed to fetch loans: " . $e->getMessage());
            return redirect()->back()->with('toast_error', 'Gagal memuat data pinjaman.Tolong coba lagi.');
        }
    }

    public function show($id): JsonResponse
    {
        $cacheKey = self::CACHE_KEY_PREFIX . 'show_' . $id;

        try {
            $loan = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($id) {
                return Loan::with([
                    'items.category:id,name',
                    'items.location:id,name,description',
                    'user:id,name',
                    'items'
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
                'message' => 'Pinjaman tidak ditemukan atau tidak dapat dimuat'
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $loan = Loan::findOrFail($id);

            DB::beginTransaction();

            $loan->items()->each(function ($item) {
                $item->status = 'READY';
                $item->save();
            });

            $loan->delete();

            DB::commit();

            // Clear relevant caches
            $this->clearLoanCache($id);
            $this->clearUserLoansCache(auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Pinjaman berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            Log::error("Failed to delete loan {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pinjaman.Tolong coba lagi.'
            ], 500);
        }
    }

    public function returnLoan(Request $request, $id)
    {
        $request->validate([
            'condition' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        try {
            $loan = Loan::with('items')->findOrFail($id);

            DB::beginTransaction();

            // 1. Create return record
            $return = Returns::create([
                'return_date' => now(),
                'condition' => $request->condition,
                'notes' => $request->notes,
                'loan_id' => $loan->id
            ]);

            // 2. Update loan status
            $loan->status = 'returned';
            $loan->save();

            // 3. Update all items status to READY
            $loan->items()->update(['status' => 'READY']);

            DB::commit();

            // Clear relevant caches
            $this->clearLoanCache($id);
            $this->clearUserLoansCache($loan->user_id);

            return response()->json([
                'success' => true,
                'message' => 'Pinjaman berhasil kembali',
                'return' => $return
            ]);
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            Log::error("Failed to return loan {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pengembalian.Tolong coba lagi.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Clear specific loan cache
     */
    protected function clearLoanCache($id)
    {
        Cache::forget(self::CACHE_KEY_PREFIX . 'show_' . $id);
    }

    /**
     * Clear all loans cache for a specific user
     */
    protected function clearUserLoansCache($userId)
    {
        // This clears all cached loan lists for the user
        $keys = Cache::getStore()->getRedis()->keys(self::CACHE_KEY_PREFIX . 'index_' . $userId . '_*');
        foreach ($keys as $key) {
            // Remove prefix from key
            $key = str_replace(config('database.redis.options.prefix'), '', $key);
            Cache::forget($key);
        }
    }

    /**
     * Clear all caches when loans are modified (callable from other controllers)
     */
    public static function clearAllManageLoansCache($userId = null)
    {
        $controller = new self();

        if ($userId) {
            $controller->clearUserLoansCache($userId);
        } else {
            // Clear all manage loans caches if no specific user ID provided
            $keys = Cache::getStore()->getRedis()->keys(self::CACHE_KEY_PREFIX . '*');
            foreach ($keys as $key) {
                $key = str_replace(config('database.redis.options.prefix'), '', $key);
                Cache::forget($key);
            }
        }
    }
}
