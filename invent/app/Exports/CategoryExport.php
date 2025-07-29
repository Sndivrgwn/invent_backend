<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class CategoryExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $boldRows = [];

    public function collection()
    {
        $data = [];
        $rowIndex = 2; // Baris 1 untuk heading

        $categories = Category::with(['items.loans'])->get();

        // Helper untuk ubah 0 jadi "Tidak Ada"
        $formatNumber = function ($value) {
            return $value === 0 ? 'Tidak Ada' : $value;
        };

        foreach ($categories as $category) {
            // Ambil item dengan kondisi GOOD
            $goodItems = $category->items->filter(function ($item) {
                return strtoupper($item->condition) === 'GOOD';
            })->unique('id');

            $total = $goodItems->count();
            $loaned = $goodItems->sum(function ($item) {
                return $item->loans->where('status', 'borrowed')->count();
            });
            $available = $total - $loaned;

            // Tambahkan baris kategori
            $data[] = [
                'Category'  => $category->name,
                'Type'      => '',
                'Quantity'  => $formatNumber($total),
                'Loaned'    => $formatNumber($loaned),
                'Available' => $formatNumber($available),
            ];
            $this->boldRows[] = $rowIndex++;

            // Ambil semua tipe dari semua item (apapun kondisinya)
            $allTypes = $category->items->pluck('type')->unique();

            foreach ($allTypes as $type) {
                // Filter hanya item GOOD per tipe
                $itemsOfType = $goodItems->where('type', $type);

                $qty = $itemsOfType->count();
                $loan = $itemsOfType->sum(function ($item) {
                    return $item->loans->where('status', 'borrowed')->count();
                });
                $avail = $qty - $loan;

                $data[] = [
                    'Category'  => '',
                    'Type'      => $type,
                    'Quantity'  => $formatNumber($qty),
                    'Loaned'    => $formatNumber($loan),
                    'Available' => $formatNumber($avail),
                ];
                $rowIndex++;
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Kategori',
            'Tipe',
            'Tersedia',
            'Dipinjamkan',
            'Sisa Stok',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $styles = [];

        // Heading bold dan background biru muda
        $styles[1] = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DCE6F1'],
            ],
        ];

        // Baris kategori bold dan abu-abu
        foreach ($this->boldRows as $row) {
            $styles[$row] = [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F2F2F2'],
                ],
            ];
        }

        return $styles;
    }
}
