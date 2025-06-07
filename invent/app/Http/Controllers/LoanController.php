<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return View('pages.loan');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'code_loans' => 'required|string|unique:loans',
            'loan_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:loan_date',
            'status' => 'required|in:dipinjam,dikembalikan,terlambat',
        ]);

        $loan = Loan::create($request->all());
        return response()->json(['message' => 'Loan created', 'loan' => $loan], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $loan = Loan::with(['user', 'item'])->find($id);
        if (!$loan) {
            return response()->json(['message' => 'Loan not found'], 404);
        }

        return response()->json($loan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $loan = Loan::find($id);
        if (!$loan) {
            return response()->json(['message' => 'Loan not found'], 404);
        }

        $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'item_id' => 'sometimes|exists:items,id',
            'quantity' => 'sometimes|integer|min:1',
            'code_loans' => 'sometimes|string|unique:loans,code_loans,' . $id,
            'loan_date' => 'sometimes|date',
            'return_date' => 'sometimes|date|after_or_equal:loan_date',
            'status' => 'sometimes|in:dipinjam,dikembalikan,terlambat',
        ]);

        $loan->update($request->all());
        return response()->json(['message' => 'Loan updated', 'loan' => $loan]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $loan = Loan::find($id);
        if (!$loan) {
            return response()->json(['message' => 'Loan not found'],  404);
        }

        $loan->delete();
        return response()->json(['message' => 'Loan deleted']);
    }
}
