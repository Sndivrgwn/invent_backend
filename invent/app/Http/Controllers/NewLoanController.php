<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class NewLoanController extends Controller
{
    public function index()
    {
        // In your controller
$items = Item::select('id', 'code', 'name', 'type', 'status')
            ->where('status', 'READY') // Only load available items
            ->get();

        return View('pages.newLoan' , compact('items'));
    }
}
