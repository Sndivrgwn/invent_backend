<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Returns; // pastikan ini sesuai
use App\Models\Loan;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalLoans = $user->loans()->count();

        $totalReturns = Returns::whereIn('loan_id', function ($query) use ($user) {
            $query->select('id')
                  ->from('loans')
                  ->where('user_id', $user->id);
        })->count();

        return view('pages.profil', compact('user', 'totalLoans', 'totalReturns'));
    }
    
    
}
