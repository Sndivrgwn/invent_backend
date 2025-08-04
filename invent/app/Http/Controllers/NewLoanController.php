<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NewLoanController extends Controller
{
    // Cache configuration
    const CACHE_TTL = 3600; // 1 hour
    const CACHE_KEY = 'available_items_for_loan';

    public function index()
    {
        try {
            $items = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
                return Item::select('id', 'code', 'name', 'type', 'status')
                    ->where('status', 'READY')
                    ->get();
            });

            return view('pages.newLoan', compact('items'));
        } catch (\Exception $e) {
            // Log error and fallback to uncached query
            \Log::error('Failed to fetch available items: ' . $e->getMessage());
            
            $items = Item::select('id', 'code', 'name', 'type', 'status')
                ->where('status', 'READY')
                ->get();

            return view('pages.newLoan', compact('items'));
        }
    }

    /**
     * Clear the available items cache
     * Call this whenever item statuses change
     */
    public static function clearAvailableItemsCache()
    {
        Cache::forget(self::CACHE_KEY);
    }
}