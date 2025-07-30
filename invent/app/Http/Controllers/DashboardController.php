<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Total semua barang
        $totalItems = Item::count();

        // Total kategori
        $totalCategories = Category::count();

        $totalInventory = Location::count();

        // Total peminjaman
        $totalLoans = Loan::count();

        // Peminjaman aktif
        $activeLoans = Loan::where('status', 'borrowed')->orWhereNull('return_date')->get();
        $totalActiveLoans = $activeLoans->count();

        // Jumlah barang yang sedang dipinjam
        $totalLoanedItems = $activeLoans->sum(fn($loan) => $loan->items->count());

        // Jumlah peminjaman yang sudah dikembalikan
        $returnedLoans = Loan::where('status', 'returned')->count();

        // 10 peminjaman terbaru
        $latestLoans = Loan::with('items')
            ->orderBy('loan_date', 'desc')
            ->take(10)
            ->get();

        // Barang paling sering dipinjam (Top 5)
        $mostLoanedItems = Item::withCount('loans')
            ->orderBy('loans_count', 'desc')
            ->take(5)
            ->get();

        // Statistik peminjaman per bulan (12 bulan terakhir)
        $loansPerMonth = Loan::select(
                DB::raw("DATE_FORMAT(loan_date, '%Y-%m') as month"),
                DB::raw("COUNT(*) as total")
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get()
            ->reverse() // agar urut dari bulan paling lama ke terbaru
            ->values();

        // Barang berdasarkan kondisi
        $itemConditions = Item::select('condition', DB::raw('COUNT(*) as total'))
            ->groupBy('condition')
            ->get();

        return view('pages.dashboard', compact(
            'totalItems',
            'totalCategories',
            'totalLoans',
            'totalActiveLoans',
            'totalLoanedItems',
            'returnedLoans',
            'totalInventory',
            'latestLoans',
            'mostLoanedItems',
            'loansPerMonth',
            'itemConditions'
        ));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        return redirect()->route('dashboard', ['search' => $search]);
    }
}
