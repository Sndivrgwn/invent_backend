<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class NewLoanController extends Controller
{
    public function index()
    {
        $items = Item::all();

        return View('pages.newLoan' , compact('items'));
    }
}
