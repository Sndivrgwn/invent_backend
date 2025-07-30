<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Buat kategori baru kalau belum ada
        $category = Category::firstOrCreate(
            ['name' => $row['category']],
            ['description' => 'Dibuat otomatis dari impor'] // opsional
        );

        // Buat lokasi baru kalau belum ada
        $location = Location::firstOrCreate(
            ['name' => $row['rack']],
            ['description' => 'Dibuat otomatis dari impor'] // opsional
        );

        return new Item([
            'name'        => $row['product'],
            'brand'       => $row['brand'],
            'type'        => $row['type'],
            'condition'   => $row['condition'],
            'category_id' => $category->id,
            'location_id' => $location->id,
            'code'      => $row['code'],
            'description' => $row['description'],
        ]);
    }
}
