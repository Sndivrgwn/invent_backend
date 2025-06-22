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

    $items = Item::all();
    $totalItems = $items->count();

    $categories = Category::all();
    $totalCategories = $categories->count();

    $loans = Loan::query();

    if ($search) {
        $loans->where('code_loans', 'like', "%{$search}%")
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
    }

    $loans = $loans->paginate(20);
    $totalLoans = $loans->total();

    // Menghitung total item yang sedang dipinjam
    $totalLoanedItems = 0;
    
    // Cara 1: Jika Anda memiliki relasi many-to-many antara Loan dan Item
    // Anda bisa menghitung jumlah item dari semua loan yang belum dikembalikan
    $activeLoans = Loan::where('status', 'borrowed') // atau kondisi lain yang sesuai
                     ->orWhereNull('return_date')
                     ->with('items')
                     ->get();
    
    foreach ($activeLoans as $loan) {
        $totalLoanedItems += $loan->items->count();
    }
    
    // Atau Cara 2: Jika Anda memiliki pivot table loan_item
    // Anda bisa menghitung langsung dari pivot table
    // $totalLoanedItems = DB::table('loan_item')
    //     ->join('loans', 'loans.id', '=', 'loan_item.loan_id')
    //     ->where('loans.status', 'Dipinjam') // atau kondisi lain
    //     ->orWhereNull('loans.return_date')
    //     ->count();

    return view('pages.dashboard', compact(
        'totalItems', 
        'totalCategories', 
        'totalLoans', 
        'loans', 
        'search',
        'totalLoanedItems'
    ));
}

    public function search(Request $request)
    {
        $search = $request->input('search');
        return redirect()->route('dashboard', ['search' => $search]);
    }
}
