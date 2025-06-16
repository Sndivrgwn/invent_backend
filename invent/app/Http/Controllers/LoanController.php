<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::with(['user', 'items'])->get();
        return View('pages.loan', compact('loans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'code_loans' => 'required|string|unique:loans',
            'loan_date' => 'required|date',
            'return_date' => 'required|date',
            'status' => 'required|string',
            'loaner_name' => 'required|string',
            'description' => 'nullable|string',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan loan utama
            $loan = Loan::create([
                'user_id' => $request->user_id,
                'code_loans' => $request->code_loans,
                'loan_date' => $request->loan_date,
                'return_date' => $request->return_date,
                'status' => $request->status,
                'loaner_name' => $request->loaner_name,
                'description' => $request->description,
            ]);

            // 2. Simpan item ke pivot table loan_item
            foreach ($request->items as $item) {
                $loan->items()->attach($item['item_id'], [
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Loan berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }




    public function search(Request $request)
    {
        $keyword = $request->query('q');

        $items = Item::where('code', 'LIKE', "%$keyword%")
            ->orWhere('name', 'LIKE', "%$keyword%")
            ->orWhere('brand', 'LIKE', "%$keyword%")
            ->orWhere('type', 'LIKE', "%$keyword%")
            ->limit(10)
            ->get();

        return response()->json($items);
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
