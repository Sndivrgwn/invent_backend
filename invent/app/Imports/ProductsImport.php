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
    $requiredKeys = ['product', 'brand', 'type', 'condition', 'category', 'rack', 'code', 'description'];

    foreach ($requiredKeys as $key) {
        if (!array_key_exists($key, $row)) {
            throw new \Exception("Kolom \"$key\" tidak ditemukan dalam file. Pastikan template sesuai format yang ditentukan.");
        }
    }

    // lanjut seperti biasa
    $category = Category::firstOrCreate(
        ['name' => $row['category']],
        ['description' => 'Dibuat otomatis dari impor']
    );

    $location = Location::firstOrCreate(
        ['name' => $row['rack']],
        ['description' => 'Dibuat otomatis dari impor']
    );

    return new Item([
        'name'        => $row['product'],
        'brand'       => $row['brand'],
        'type'        => $row['type'],
        'condition'   => $row['condition'],
        'category_id' => $category->id,
        'location_id' => $location->id,
        'code'        => $row['code'],
        'description' => $row['description'],
    ]);
}

}
