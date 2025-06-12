<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewLoanController extends Controller
{
    public function index()
    {
        return View('pages.newLoan');
    }
}
