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
    const DEFAULT_CACHE_TTL = 1800; // 30 minutes
    const CACHE_KEY_PREFIX = 'manage_loans_';
    const CACHE_VERSION = 'v1_';
    const CACHE_TAG = 'manage_loans';

    public function index()
    {
        try {
            $search = request('search-navbar');
            $sortBy = request('sortBy', 'loan_date');
            $sortDir = request('sortDir', 'desc');
            
            $cacheKey = $this->generateCacheKey('index_' . auth()->id() . '_' . md5(json_encode([
                'search' => $search,
                'sortBy' => $sortBy,
                'sortDir' => $sortDir,
                'page' => request('page', 1)
            ])));

            $myloans = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () use ($search, $sortBy, $sortDir) {
                Log::debug('Cache miss for manage loans index: ' . auth()->id());
                $allowedSorts = ['loan_date', 'code_loans', 'loaner_name', 'return_date'];
                
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
            Log::error("Failed to fetch loans: " . $e->getMessage());
            return redirect()->back()->with('toast_error', 'Gagal memuat data pinjaman.Tolong coba lagi.');
        }
    }

    public function show($id): JsonResponse
    {
        $cacheKey = $this->generateCacheKey('show_' . $id);

        try {
            $loan = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () use ($id) {
                Log::debug('Cache miss for manage loan show: ' . $id);
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

            Cache::tags([self::CACHE_TAG, 'loans'])->flush();
            Log::debug('Cleared manage loans and loans cache after delete');

            return response()->json([
                'success' => true,
                'message' => 'Pinjaman berhasil dihapus'
            ]);
        } catch (\Exception $e) {
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

            $return = Returns::create([
                'return_date' => now(),
                'condition' => $request->condition,
                'notes' => $request->notes,
                'loan_id' => $loan->id
            ]);

            $loan->status = 'returned';
            $loan->save();

            $loan->items()->update(['status' => 'READY']);

            DB::commit();

            Cache::tags([self::CACHE_TAG, 'loans', 'returns'])->flush();
            Log::debug('Cleared manage loans, loans and returns cache after return');

            return response()->json([
                'success' => true,
                'message' => 'Pinjaman berhasil kembali',
                'return' => $return
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to return loan {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pengembalian.Tolong coba lagi.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    protected function generateCacheKey(string $key): string
    {
        return self::CACHE_VERSION . self::CACHE_KEY_PREFIX . $key;
    }

    public static function clearAllManageLoansCache($userId = null)
    {
        if ($userId) {
            $keys = Cache::getStore()->getRedis()->keys(
                config('database.redis.options.prefix') . 
                self::CACHE_VERSION . 
                self::CACHE_KEY_PREFIX . 
                'index_' . $userId . '_*'
            );
            foreach ($keys as $key) {
                $key = str_replace(config('database.redis.options.prefix'), '', $key);
                Cache::tags(self::CACHE_TAG)->forget($key);
            }
        } else {
            Cache::tags(self::CACHE_TAG)->flush();
        }
        Log::debug('Cleared manage loans cache for user: ' . ($userId ?? 'all'));
    }
}