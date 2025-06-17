<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Loan;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class HistoryExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        // Ambil semua loan beserta user dan items
        $loans = Loan::with(['user', 'items.category'])->get();

        $data = [];

        foreach ($loans as $loan) {
            foreach ($loan->items as $item) {
                $data[] = [
                    'Loan Code' => $loan->code_loans,
                    'User' => $loan->user->name ?? '-',
                    'loaner_name' => $loan->loaner_name,
                    'status' => $loan->status,
                    'Loan Date' => $loan->loan_date,
                    'Return Date' => $loan->return_date,
                    'Item' => $item->name,
                    'type' => $item->type,
                    'item_code' => $item->code,
                    'Category' => $item->category->name ?? '-',
                    'Quantity' => $item->pivot->quantity ?? 1,
                ];
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Loan Code',
            'User',
            'Loaner Name',
            'Status',
            'Loan Date',
            'Return Date',
            'Item',
            'Type',
            'Item Code',
            'Category',
            'Quantity',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'DDEBF7'],
                ],
            ],
        ];
    }
}
