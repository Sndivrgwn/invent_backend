<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $items = item::all();
        $totalItems = $items->count();

        $categories = Category::all();
        $totalCategories = $categories->count();

        $loans = Loan::all();
        $totalLoans = $loans->count();
        

        return view('pages.setting', compact('totalItems', 'totalCategories', 'totalLoans'));
    }
}
