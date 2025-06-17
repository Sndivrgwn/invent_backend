<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $items = item::all();
        $totalItems = $items->count();

        $categories = Category::all();
        $totalCategories = $categories->count();

        $loans = Loan::paginate(20);
        $totalLoans = $loans->count();

        return view('pages.dashboard', compact('totalItems', 'totalCategories', 'totalLoans', 'loans'));
    }
}
