<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Returns;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ReturnController extends Controller
{
    // Cache configuration
    const CACHE_TTL = 3600; // 1 hour
    const CACHE_KEY_PREFIX = 'returns_';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cacheKey = self::CACHE_KEY_PREFIX . 'all';
        
        $returns = Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return Returns::all();
        });

        return response()->json($returns, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'return_date' => 'required|date',
            'condition' => 'required|in:baik,rusak,hilang',
            'notes' => 'nullable|string',
        ]);

        // Get the loan first to update its status
        $loan = Loan::findOrFail($request->loan_id);
        
        DB::beginTransaction();
        try {
            // Create the return record
            $data = Returns::create($request->all());
            
            // Update the loan status
            $loan->status = 'returned';
            $loan->save();
            
            // Update all items status to READY
            $loan->items()->update(['status' => 'READY']);
            
            DB::commit();

            // Clear relevant caches
            $this->clearReturnsCache();
            Cache::forget(self::CACHE_KEY_PREFIX . 'loan_' . $request->loan_id);

            return response()->json([
                'message' => 'Pengembalian dibuat', 
                'data' => $data
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal membuat pengembalian',
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
        
        $data = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($id) {
            return Returns::with('loan')->find($id);
        });

        if (!$data) {
            return response()->json(['message' => 'Pengembalian tidak ditemukan'], 404);
        }

        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Returns::find($id);
        if (!$data) {
            return response()->json(['message' => 'Pengembalian tidak ditemukan'], 404);
        }

        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'return_date' => 'required|date',
            'condition' => 'required|in:baik,rusak,hilang',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $data->update($request->all());
            
            // Clear relevant caches
            $this->clearReturnsCache();
            $this->clearReturnCache($id);
            Cache::forget(self::CACHE_KEY_PREFIX . 'loan_' . $request->loan_id);
            
            DB::commit();

            return response()->json([
                'message' => 'Pengembalian diperbarui', 
                'data' => $data
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal memperbarui pengembalian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Returns::find($id);
        if (!$data) {
            return response()->json(['message' => 'Pengembalian tidak ditemukan'], 404);
        }

        DB::beginTransaction();
        try {
            $loanId = $data->loan_id;
            $data->delete();
            
            // Clear relevant caches
            $this->clearReturnsCache();
            $this->clearReturnCache($id);
            Cache::forget(self::CACHE_KEY_PREFIX . 'loan_' . $loanId);
            
            DB::commit();

            return response()->json(['message' => 'Pengembalian dihapus'], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menghapus pengembalian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all returns cache
     */
    protected function clearReturnsCache()
    {
        Cache::forget(self::CACHE_KEY_PREFIX . 'all');
    }

    /**
     * Clear specific return cache
     */
    protected function clearReturnCache($id)
    {
        Cache::forget(self::CACHE_KEY_PREFIX . 'show_' . $id);
    }

    /**
     * Clear all caches when returns are modified (callable from other controllers)
     */
    public static function clearAllReturnsCache()
    {
        $keys = [
            'all',
            // Add any other cache keys used in this controller
        ];
        
        foreach ($keys as $key) {
            Cache::forget(self::CACHE_KEY_PREFIX . $key);
        }
    }
}