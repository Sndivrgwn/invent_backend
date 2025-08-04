<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NewLoanController extends Controller
{
    // Cache configuration
    const DEFAULT_CACHE_TTL = 3600; // 1 hour
    const CACHE_KEY_PREFIX = 'available_items_';
    const CACHE_VERSION = 'v1_';
    const CACHE_TAG = 'available_items';

    public function index()
    {
        $cacheKey = $this->generateCacheKey('for_loan');

        try {
            $items = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () {
                Log::debug('Cache miss for available items');
                return Item::select('id', 'code', 'name', 'type', 'status')
                    ->where('status', 'READY')
                    ->get();
            });

            return view('pages.newLoan', compact('items'));
        } catch (\Exception $e) {
            Log::error('Failed to fetch available items: ' . $e->getMessage());
            
            $items = Item::select('id', 'code', 'name', 'type', 'status')
                ->where('status', 'READY')
                ->get();

            return view('pages.newLoan', compact('items'));
        }
    }

    protected function generateCacheKey(string $key): string
    {
        return self::CACHE_VERSION . self::CACHE_KEY_PREFIX . $key;
    }

    public static function clearAvailableItemsCache()
    {
        Cache::tags(self::CACHE_TAG)->flush();
        Log::debug('Cleared available items cache');
    }
}