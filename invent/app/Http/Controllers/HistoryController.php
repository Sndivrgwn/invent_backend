<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Location;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $loans = Loan::query();
        $locations = Location::all();

        if ($search) {
            $loans->where(function ($query) use ($search) {
                $query->where('code_loans', 'like', "%{$search}%")
                    ->orWhere('loaner_name', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('loan_date', 'like', "%{$search}%")
                    ->orWhere('return_date', 'like', "%{$search}%")
                    ->orWhereHas('items', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('type', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%")
                            ->orWhereHas('category', function ($query) use ($search) {
                                $query->where('name', 'like', "%{$search}%");
                            });
                    })
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Tambahkan filter tanggal
        if ($startDate) {
            $loans->whereDate('loan_date', '>=', $startDate);
        }

        if ($endDate) {
            $loans->whereDate('loan_date', '<=', $endDate);
        }

        $loans = $loans->with('items.category')->paginate(20);

        return view('pages.history', compact('loans', 'search', 'locations'));
    }


    public function filter(Request $request)
    {
        $loans = Loan::with(['items.location', 'items.category'])
            ->whereHas('items', function ($query) use ($request) {
                $query->when($request->brand, fn($q) => $q->where('brand', $request->brand))
                    ->when($request->type, fn($q) => $q->where('type', $request->type))
                    ->when($request->condition, fn($q) => $q->where('condition', $request->condition))
                    ->when($request->category, function ($q) use ($request) {
                        $q->whereHas('category', fn($q) => $q->where('name', $request->category));
                    })
                    ->when($request->location, function ($q) use ($request) {
                        $q->whereHas('location', fn($q) => $q->where('description', $request->location));
                    });
            })
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->get();

        return response()->json($loans);
    }
}
