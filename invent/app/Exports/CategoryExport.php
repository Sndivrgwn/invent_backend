<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CategoryExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $categories = Category::withCount('items')->get();

        // Transform data
        return $categories->map(function ($category) {
            return [
                'Category Name' => $category->name,
                'Item Count' => $category->items_count,
                'Low Stock' => $category->items_count < 3 ? 'Yes' : 'No',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Category Name',
            'Item Count',
            'Low Stock',
        ];
    }
}
