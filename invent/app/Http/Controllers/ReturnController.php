<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Returns;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReturnController extends Controller
{
    // Cache configuration
    const DEFAULT_CACHE_TTL = 3600; // 1 hour
    const CACHE_KEY_PREFIX = 'returns_';
    const CACHE_VERSION = 'v1_';
    const CACHE_TAG = 'returns';

    public function index()
    {
        $cacheKey = $this->generateCacheKey('all');
        
        $returns = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () {
            Log::debug('Cache miss for returns index');
            return Returns::all();
        });

        return response()->json($returns, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'return_date' => 'required|date',
            'condition' => 'required|in:baik,rusak,hilang',
            'notes' => 'nullable|string',
        ]);

        $loan = Loan::findOrFail($request->loan_id);
        
        DB::beginTransaction();
        try {
            $data = Returns::create($request->all());
            
            $loan->status = 'returned';
            $loan->save();
            
            $loan->items()->update(['status' => 'READY']);
            
            DB::commit();

            Cache::tags([self::CACHE_TAG, 'loans'])->flush();
            Log::debug('Cleared returns and loans cache after store');

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

    public function show(string $id)
    {
        $cacheKey = $this->generateCacheKey('show_' . $id);
        
        $data = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () use ($id) {
            Log::debug('Cache miss for return show: ' . $id);
            return Returns::with('loan')->find($id);
        });

        if (!$data) {
            return response()->json(['message' => 'Pengembalian tidak ditemukan'], 404);
        }

        return response()->json($data, 200);
    }

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
            
            Cache::tags([self::CACHE_TAG, 'loans'])->flush();
            Log::debug('Cleared returns and loans cache after update');
            
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
            
            Cache::tags([self::CACHE_TAG, 'loans'])->flush();
            Log::debug('Cleared returns and loans cache after delete');
            
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

    protected function generateCacheKey(string $key): string
    {
        return self::CACHE_VERSION . self::CACHE_KEY_PREFIX . $key;
    }

    public static function clearAllReturnsCache()
    {
        Cache::tags(self::CACHE_TAG)->flush();
        Log::debug('Cleared all returns cache using static method');
    }
}