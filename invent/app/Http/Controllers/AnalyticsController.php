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
    // Ambil semua kategori beserta item dan loan
    $categories = Category::with(['items', 'items.loans'])->get();

    foreach ($categories as $category) {
        // Hitung total item dalam kategori
        $category->items_count = $category->items->count();

        // Hitung jumlah peminjaman dalam kategori
        $category->loan_count = $category->items->reduce(function ($carry, $item) {
            return $carry + $item->loans->count();
        }, 0);

        // Hitung jumlah tersedia
        $category->available_count = $category->items_count - $category->loan_count;

        // Tandai stok rendah
        $category->low_stock = $category->available_count < 3 ? 'Yes' : 'No';

        // Kelompokkan item berdasarkan tipe
        $types = $category->items->groupBy('type')->map(function ($items) {
            $total = $items->count();
            $loaned = $items->reduce(fn($carry, $item) => $carry + $item->loans->count(), 0);
            $available = $total - $loaned;
            $lowStock = $available < 3 ? 'Yes' : 'No';

            return [
                'type' => $items->first()->type,
                'quantity' => $total,
                'available' => $available,
                'loaned' => $loaned,
                'low_stock' => $lowStock,
            ];
        });

        // Simpan data ke kategori
        $category->type_summaries = $types->values(); // array numerik untuk loop di Blade
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
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($request->only('name', 'description'));

        return redirect()->route('analytics')->with('success', 'Category created successfully.'); 
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
