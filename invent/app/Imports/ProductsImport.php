<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithHeadingRow
{
    public int $importedRows = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Lewati baris kosong total
            if (array_filter($row->toArray()) === []) {
                continue;
            }

            $requiredKeys = ['produk', 'brand', 'tipe', 'kondisi', 'kategori', 'rak', 'kode'];

            foreach ($requiredKeys as $key) {
                if (!isset($row[$key]) || trim($row[$key]) === '') {
                    throw new \Exception("Kolom \"$key\" wajib diisi dan tidak boleh kosong.");
                }
            }

            $category = Category::firstOrCreate(
                ['name' => $row['kategori']],
                ['description' => 'Dibuat otomatis dari impor']
            );

            $location = Location::firstOrCreate(
                ['name' => $row['rak']],
                ['description' => 'Dibuat otomatis dari impor']
            );

            Item::create([
                'name'        => $row['produk'],
                'brand'       => $row['brand'],
                'type'        => $row['tipe'],
                'condition'   => $row['kondisi'],
                'category_id' => $category->id,
                'location_id' => $location->id,
                'code'        => $row['kode'],
                'description' => $row['deskripsi'] ?? '',
            ]);

            $this->importedRows++;
        }
    }
}
