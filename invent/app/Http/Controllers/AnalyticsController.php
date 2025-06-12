<?php

namespace App\Http\Controllers;

use App\Exports\CategoryExport;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua kategori dengan jumlah item
        $categories = Category::withCount('items')->get();

        // Ambil semua data peminjaman
        $loans = Loan::with('item')->get();

        // Hitung per kategori
        foreach ($categories as $category) {
            // Ambil semua loan yang item-nya termasuk dalam kategori ini
            
            $loanCount = $loans->filter(function ($loan) use ($category) {
                return $loan->item && $loan->item->category_id === $category->id;
            })->count();
            $category->loan_count = $loanCount;
            $category->available_count = $category->items_count - $loanCount;
            $category->low_stock = $category->available_count < 3 ? 'Yes' : 'No';
        }

        return view('pages.analytics', compact('categories'));
    }



    public function export()
    {
        return Excel::download(new CategoryExport, 'categories_report.xlsx');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
