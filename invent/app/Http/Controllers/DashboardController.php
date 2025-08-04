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

class DashboardController extends Controller
{
    // Cache configuration
    const DEFAULT_CACHE_TTL = 1800; // 30 minutes
    const CACHE_KEY_PREFIX = 'dashboard_';
    const CACHE_VERSION = 'v1_';
    const CACHE_TAG = 'dashboard';

    public function index(Request $request)
    {
        $cacheKey = $this->generateCacheKey('main_data');
        
        $data = Cache::tags(self::CACHE_TAG)->remember($cacheKey, self::DEFAULT_CACHE_TTL, function () {
            Log::debug('Cache miss for dashboard main data');
            return $this->getDashboardData();
        });

        return view('pages.dashboard', $data);
    }

    protected function getDashboardData()
    {
        return [
            'totalItems' => $this->getTotalItems(),
            'totalCategories' => $this->getTotalCategories(),
            'totalInventory' => $this->getTotalInventory(),
            'totalLoans' => $this->getTotalLoans(),
            'totalActiveLoans' => $this->getTotalActiveLoans(),
            'totalLoanedItems' => $this->getTotalLoanedItems(),
            'returnedLoans' => $this->getReturnedLoans(),
            'latestLoans' => $this->getLatestLoans(),
            'mostLoanedItems' => $this->getMostLoanedItems(),
            'loansPerMonth' => $this->getLoansPerMonth(),
            'itemConditions' => $this->getItemConditions(),
        ];
    }

    protected function getTotalItems()
    {
        return Cache::tags([self::CACHE_TAG, 'items'])->remember(
            $this->generateCacheKey('total_items'), 
            self::DEFAULT_CACHE_TTL, 
            function () {
                Log::debug('Cache miss for dashboard total items');
                return Item::count();
            }
        );
    }

    protected function getTotalCategories()
    {
        return Cache::tags([self::CACHE_TAG, 'categories'])->remember(
            $this->generateCacheKey('total_categories'), 
            self::DEFAULT_CACHE_TTL, 
            function () {
                Log::debug('Cache miss for dashboard total categories');
                return Category::count();
            }
        );
    }

    protected function getTotalInventory()
    {
        return Cache::tags([self::CACHE_TAG, 'locations'])->remember(
            $this->generateCacheKey('total_inventory'), 
            self::DEFAULT_CACHE_TTL, 
            function () {
                Log::debug('Cache miss for dashboard total inventory');
                return Location::count();
            }
        );
    }

    protected function getTotalLoans()
    {
        return Cache::tags([self::CACHE_TAG, 'loans'])->remember(
            $this->generateCacheKey('total_loans'), 
            self::DEFAULT_CACHE_TTL, 
            function () {
                Log::debug('Cache miss for dashboard total loans');
                return Loan::count();
            }
        );
    }

    protected function getTotalActiveLoans()
    {
        return Cache::tags([self::CACHE_TAG, 'loans'])->remember(
            $this->generateCacheKey('active_loans'), 
            self::DEFAULT_CACHE_TTL, 
            function () {
                Log::debug('Cache miss for dashboard active loans');
                return Loan::where('status', 'borrowed')->orWhereNull('return_date')->count();
            }
        );
    }

    protected function getTotalLoanedItems()
    {
        return Cache::tags([self::CACHE_TAG, 'loans', 'items'])->remember(
            $this->generateCacheKey('loaned_items'), 
            self::DEFAULT_CACHE_TTL, 
            function () {
                Log::debug('Cache miss for dashboard loaned items');
                return Loan::where('status', 'borrowed')
                    ->orWhereNull('return_date')
                    ->withCount('items')
                    ->get()
                    ->sum('items_count');
            }
        );
    }

    protected function getReturnedLoans()
    {
        return Cache::tags([self::CACHE_TAG, 'loans'])->remember(
            $this->generateCacheKey('returned_loans'), 
            self::DEFAULT_CACHE_TTL, 
            function () {
                Log::debug('Cache miss for dashboard returned loans');
                return Loan::where('status', 'returned')->count();
            }
        );
    }

    protected function getLatestLoans()
    {
        return Cache::tags([self::CACHE_TAG, 'loans'])->remember(
            $this->generateCacheKey('latest_loans'), 
            self::DEFAULT_CACHE_TTL, 
            function () {
                Log::debug('Cache miss for dashboard latest loans');
                return Loan::with('items')
                    ->orderBy('loan_date', 'desc')
                    ->take(10)
                    ->get();
            }
        );
    }

    protected function getMostLoanedItems()
    {
        return Cache::tags([self::CACHE_TAG, 'items'])->remember(
            $this->generateCacheKey('most_loaned_items'), 
            self::DEFAULT_CACHE_TTL, 
            function () {
                Log::debug('Cache miss for dashboard most loaned items');
                return Item::withCount('loans')
                    ->orderBy('loans_count', 'desc')
                    ->take(5)
                    ->get();
            }
        );
    }

    protected function getLoansPerMonth()
    {
        return Cache::tags([self::CACHE_TAG, 'loans'])->remember(
            $this->generateCacheKey('loans_per_month'), 
            self::DEFAULT_CACHE_TTL, 
            function () {
                Log::debug('Cache miss for dashboard loans per month');
                return Loan::select(
                        DB::raw("DATE_FORMAT(loan_date, '%Y-%m') as month"),
                        DB::raw("COUNT(*) as total")
                    )
                    ->groupBy('month')
                    ->orderBy('month', 'desc')
                    ->limit(12)
                    ->get()
                    ->reverse()
                    ->values();
            }
        );
    }

    protected function getItemConditions()
    {
        return Cache::tags([self::CACHE_TAG, 'items'])->remember(
            $this->generateCacheKey('item_conditions'), 
            self::DEFAULT_CACHE_TTL, 
            function () {
                Log::debug('Cache miss for dashboard item conditions');
                return Item::select('condition', DB::raw('COUNT(*) as total'))
                    ->groupBy('condition')
                    ->get();
            }
        );
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        return redirect()->route('dashboard', ['search' => $search]);
    }

    protected function generateCacheKey(string $key): string
    {
        return self::CACHE_VERSION . self::CACHE_KEY_PREFIX . $key;
    }

    public static function clearDashboardCaches()
    {
        Cache::tags(self::CACHE_TAG)->flush();
        Log::debug('Cleared all dashboard caches');
    }
}