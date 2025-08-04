<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductsController extends Controller
{
    // Cache configuration
    const CACHE_TTL = 3600; // 1 hour
    const CACHE_KEY_PREFIX = 'products_';

    public function index()
    {
        try {
            // Get all cached data
            $data = Cache::remember(self::CACHE_KEY_PREFIX . 'dashboard_data', self::CACHE_TTL, function () {
                return [
                    'totalItems' => Item::count(),
                    'totalCategories' => Category::count(),
                    'totalLoans' => Loan::count(),
                    'locations' => Location::all(),
                ];
            });

            return view('pages.products', $data);
            
        } catch (\Exception $e) {
            // Fallback to uncached queries if caching fails
            $data = [
                'totalItems' => Item::count(),
                'totalCategories' => Category::count(),
                'totalLoans' => Loan::count(),
                'locations' => Location::all(),
            ];
            
            return view('pages.products', $data);
        }
    }

    /**
     * Clear all products-related caches
     */
    public static function clearProductsCache()
    {
        Cache::forget(self::CACHE_KEY_PREFIX . 'dashboard_data');
    }
}