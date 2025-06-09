<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use App\Models\Location;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        $items = item::all();
        $totalItems = $items->count();

        $categories = Category::all();
        $totalCategories = $categories->count();

        $loans = Loan::all();
        $totalLoans = $loans->count();
        
        $location = Location::all();

        return view('pages.products', compact('totalItems', 'totalCategories', 'totalLoans' , 'location'));
    }
}
