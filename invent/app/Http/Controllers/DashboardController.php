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
        $totalLoans = $loans->total(); // bukan ->count(), karena count() hanya untuk halaman itu

        return view('pages.dashboard', compact('totalItems', 'totalCategories', 'totalLoans', 'loans', 'search'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        return redirect()->route('dashboard', ['search' => $search]);
    }
}
