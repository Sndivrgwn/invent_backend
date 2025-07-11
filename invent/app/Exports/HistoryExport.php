<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Loan;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class HistoryExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithMapping
{
    public function collection()
    {
        return Loan::with(['user', 'items.category', 'items.location', 'return'])
            ->where('status', 'returned')
            ->orderBy('loan_date', 'desc')
            ->get();
    }

    public function map($loan): array
    {
        $mappedData = [];
        
        foreach ($loan->items as $item) {
            $mappedData[] = [
                $loan->loan_date,
                $loan->return?->return_date ?? '',
                $loan->return_date,
                $loan->code_loans,
                $loan->loaner_name,
                $loan->user->name ?? 'N/A',
                $item->code,
                $item->name,
                $loan->status,
                $item->category->name ?? '-',
                $item->location->description ?? '-',
                $item->brand,
                $item->type,
                $item->condition,
                $loan->description,
                $loan->return?->notes ?? '',
            ];
        }

        return $mappedData;
    }

    public function headings(): array
    {
        return [
            'Loan Date',
            'Returned At',
            'Return Date',
            'Loan Code',
            'Borrower Name',
            'Admin',
            'Serial Number',
            'Product Name',
            'Status',
            'Category',
            'Location',
            'Brand',
            'Type',
            'Condition',
            'Loan Description',
            'Return Notes',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set header style
        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F81BD'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Set data rows style
        $sheet->getStyle('A2:O' . ($sheet->getHighestRow()))
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'D3D3D3'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

        // Set alternate row colors
        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $fillColor = $row % 2 == 0 ? 'FFFFFF' : 'E6E6E6';
            $sheet->getStyle('A' . $row . ':O' . $row)
                ->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->setStartColor(new Color($fillColor));
        }

        // Freeze header row
        $sheet->freezePane('A2');

        // Set column widths (auto-size plus some additional logic)
        foreach (range('A', 'O') as $column) {
            $sheet->getColumnDimension($column)
                ->setAutoSize(true)
                ->setWidth(15);
        }

        // Set specific column widths
        $sheet->getColumnDimension('A')->setWidth(12); // Loan Date
        $sheet->getColumnDimension('B')->setWidth(12); // Returned At
        $sheet->getColumnDimension('C')->setWidth(12); // Return Date
        $sheet->getColumnDimension('D')->setWidth(15); // Loan Code
        $sheet->getColumnDimension('N')->setWidth(20); // Loan Description
        $sheet->getColumnDimension('O')->setWidth(20); // Return Notes

        return [];
    }
}