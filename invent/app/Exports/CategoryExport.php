<?php

namespace App\Exports;

use App\Models\Category;
use App\Models\Loan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoryExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Ambil semua kategori dengan jumlah item
        $categories = Category::withCount('items')->get();

        // Ambil semua data peminjaman beserta item-nya
        $loans = Loan::with('item')->get();

        // Hitung dan transformasi data per kategori
        return $categories->map(function ($category) use ($loans) {
            $loanCount = $loans->filter(function ($loan) use ($category) {
                return $loan->item && $loan->item->category_id === $category->id;
            })->count();

            $available = $category->items_count - $loanCount;

            return [
                'Category Name' => $category->name,
                'Item Count' => $category->items_count,
                'Loan Count' => $loanCount,
                'Available Count' => $available,
                'Low Stock' => $available < 3 ? 'Yes' : 'No',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Category Name',
            'Item Count',
            'Loan Count',
            'Available Count',
            'Low Stock',
        ];
    }
}
