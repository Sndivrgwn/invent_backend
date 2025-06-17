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
        $rowIndex = 2; // Baris pertama untuk heading

        $categories = Category::with(['items', 'items.loans'])->get();

        foreach ($categories as $category) {
            $total = $category->items->count();
            $loaned = $category->items->reduce(fn($carry, $item) => $carry + $item->loans->count(), 0);
            $available = $total - $loaned;
            $lowStock = $available < 3 ? 'Yes' : 'No';

            // Tambah baris kategori (akan dibold dan diberi warna)
            $data[] = [
                'Category' => $category->name,
                'Type' => '',
                'Quantity' => $total,
                'Loaned' => $loaned,
                'Available' => $available,
                'Low Stock' => $lowStock,
            ];
            $this->boldRows[] = $rowIndex++;
            
            // Tambah per type
            $types = $category->items->groupBy('type')->map(function ($items) {
                $qty = $items->count();
                $loan = $items->reduce(fn($carry, $item) => $carry + $item->loans->count(), 0);
                $avail = $qty - $loan;
                $low = $avail < 3 ? 'Yes' : 'No';

                return [
                    'type' => $items->first()->type,
                    'quantity' => $qty,
                    'loaned' => $loan,
                    'available' => $avail,
                    'low_stock' => $low,
                ];
            });

            foreach ($types as $type) {
                $data[] = [
                    'Category' => '',
                    'Type' => $type['type'],
                    'Quantity' => $type['quantity'],
                    'Loaned' => $type['loaned'],
                    'Available' => $type['available'],
                    'Low Stock' => $type['low_stock'],
                ];
                $rowIndex++;
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Category',
            'Type',
            'Quantity',
            'Loaned',
            'Available',
            'Low Stock',
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

        // Style untuk baris kategori
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
