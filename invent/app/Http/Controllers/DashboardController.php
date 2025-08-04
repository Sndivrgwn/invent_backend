<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    // Cache configuration
    const CACHE_TTL = 1800; // 30 minutes
    const CACHE_KEY_PREFIX = 'dashboard_';

    public function index(Request $request)
    {
        // Cache the entire dashboard data with a single key
        $cacheKey = self::CACHE_KEY_PREFIX . 'main_data';
        
        $data = Cache::remember($cacheKey, self::CACHE_TTL, function () {
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
        return Cache::remember(self::CACHE_KEY_PREFIX . 'total_items', self::CACHE_TTL, function () {
            return Item::count();
        });
    }

    protected function getTotalCategories()
    {
        return Cache::remember(self::CACHE_KEY_PREFIX . 'total_categories', self::CACHE_TTL, function () {
            return Category::count();
        });
    }

    protected function getTotalInventory()
    {
        return Cache::remember(self::CACHE_KEY_PREFIX . 'total_inventory', self::CACHE_TTL, function () {
            return Location::count();
        });
    }

    protected function getTotalLoans()
    {
        return Cache::remember(self::CACHE_KEY_PREFIX . 'total_loans', self::CACHE_TTL, function () {
            return Loan::count();
        });
    }

    protected function getTotalActiveLoans()
    {
        return Cache::remember(self::CACHE_KEY_PREFIX . 'active_loans', self::CACHE_TTL, function () {
            $activeLoans = Loan::where('status', 'borrowed')->orWhereNull('return_date')->get();
            return $activeLoans->count();
        });
    }

    protected function getTotalLoanedItems()
    {
        return Cache::remember(self::CACHE_KEY_PREFIX . 'loaned_items', self::CACHE_TTL, function () {
            $activeLoans = Loan::where('status', 'borrowed')->orWhereNull('return_date')->get();
            return $activeLoans->sum(fn($loan) => $loan->items->count());
        });
    }

    protected function getReturnedLoans()
    {
        return Cache::remember(self::CACHE_KEY_PREFIX . 'returned_loans', self::CACHE_TTL, function () {
            return Loan::where('status', 'returned')->count();
        });
    }

    protected function getLatestLoans()
    {
        return Cache::remember(self::CACHE_KEY_PREFIX . 'latest_loans', self::CACHE_TTL, function () {
            return Loan::with('items')
                ->orderBy('loan_date', 'desc')
                ->take(10)
                ->get();
        });
    }

    protected function getMostLoanedItems()
    {
        return Cache::remember(self::CACHE_KEY_PREFIX . 'most_loaned_items', self::CACHE_TTL, function () {
            return Item::withCount('loans')
                ->orderBy('loans_count', 'desc')
                ->take(5)
                ->get();
        });
    }

    protected function getLoansPerMonth()
    {
        return Cache::remember(self::CACHE_KEY_PREFIX . 'loans_per_month', self::CACHE_TTL, function () {
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
        });
    }

    protected function getItemConditions()
    {
        return Cache::remember(self::CACHE_KEY_PREFIX . 'item_conditions', self::CACHE_TTL, function () {
            return Item::select('condition', DB::raw('COUNT(*) as total'))
                ->groupBy('condition')
                ->get();
        });
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        return redirect()->route('dashboard', ['search' => $search]);
    }

    /**
     * Clear all dashboard caches
     * Call this whenever relevant data changes (loans, items, etc.)
     */
    public static function clearDashboardCaches()
    {
        $keys = [
            'main_data',
            'total_items',
            'total_categories',
            'total_inventory',
            'total_loans',
            'active_loans',
            'loaned_items',
            'returned_loans',
            'latest_loans',
            'most_loaned_items',
            'loans_per_month',
            'item_conditions'
        ];

        foreach ($keys as $key) {
            Cache::forget(self::CACHE_KEY_PREFIX . $key);
        }
    }
}