<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');

    $loans = Loan::query();

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

    $loans = $loans->with('items.category')->paginate(20);

    return view('pages.history', compact('loans', 'search'));
}

public function filter(Request $request)
{
    $status = $request->input('status');
    $search = $request->input('search');

    $loans = Loan::query();

    if ($status) {
        $loans->where('status', $status);
    }

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

    $loans = $loans->with('items.category')->paginate(20);

    return view('pages.history', compact('loans', 'status', 'search'));
}

}
