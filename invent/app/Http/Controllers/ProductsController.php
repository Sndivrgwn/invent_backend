<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ProductsController extends Controller
{
    // Cache configuration
    const DEFAULT_CACHE_TTL = 1800; // 30 minutes
    const CACHE_KEY_PREFIX = 'products_';
    const CACHE_VERSION = 'v1_';
    const CACHE_TAG = 'products';

    public function index()
    {
        $cacheKey = $this->generateCacheKey('dashboard_data');
        
        try {
            $data = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () {
                Log::debug('Cache miss for products dashboard');
                return [
                    'totalItems' => Item::count(),
                    'totalCategories' => Category::count(),
                    'totalLoans' => Loan::count(),
                    'locations' => Location::all(),
                ];
            });

            return view('pages.products', $data);
            
        } catch (\Exception $e) {
            Log::error('Products cache failed: ' . $e->getMessage());
            $data = [
                'totalItems' => Item::count(),
                'totalCategories' => Category::count(),
                'totalLoans' => Loan::count(),
                'locations' => Location::all(),
            ];
            
            return view('pages.products', $data);
        }
    }

    protected function generateCacheKey(string $key): string
    {
        return self::CACHE_VERSION . self::CACHE_KEY_PREFIX . $key;
    }

    public static function clearProductsCache()
    {
        Cache::tags(self::CACHE_TAG)->flush();
        Log::debug('Cleared products cache');
    }
}