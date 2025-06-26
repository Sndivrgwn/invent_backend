<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    $sortBy = $request->input('sortBy', 'loan_date'); // default sort
    $sortDir = $request->input('sortDir', 'asc');     // default ascending

    $items = Item::all();
    $totalItems = $items->count();

    $categories = Category::all();
    $totalCategories = $categories->count();

    $loans = Loan::with('items');

    if ($search) {
        $loans->where(function ($query) use ($search) {
            $query->where('code_loans', 'like', "%{$search}%")
                ->orWhere('loaner_name', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhere('loan_date', 'like', "%{$search}%")
                ->orWhere('return_date', 'like', "%{$search}%")
                ->orWhereHas('items', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                })
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    // Tambahkan sort
    if (in_array($sortBy, ['loan_date', 'loaner_name'])) {
        $loans->orderBy($sortBy, $sortDir);
    }

    $loans = $loans->paginate(20)->appends([
        'search' => $search,
        'sortBy' => $sortBy,
        'sortDir' => $sortDir,
    ]);

    $totalLoans = $loans->total();

    $activeLoans = Loan::where('status', 'borrowed')
                     ->orWhereNull('return_date')
                     ->with('items')
                     ->get();

    $totalLoanedItems = $activeLoans->sum(fn($loan) => $loan->items->count());

    return view('pages.dashboard', compact(
        'totalItems',
        'totalCategories',
        'totalLoans',
        'loans',
        'search',
        'totalLoanedItems',
        'sortBy',
        'sortDir'
    ));
}


    public function search(Request $request)
    {
        $search = $request->input('search');
        return redirect()->route('dashboard', ['search' => $search]);
    }
}
