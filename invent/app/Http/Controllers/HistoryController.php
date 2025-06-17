<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        $loans = Loan::paginate(20);

        return view('pages.history', compact('loans'));
    }
}
