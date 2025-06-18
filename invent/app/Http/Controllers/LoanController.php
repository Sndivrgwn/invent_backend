<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\HistoryExport;
use Maatwebsite\Excel\Facades\Excel;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $search = $request->input('search-navbar');

    $incomingLoans = Loan::with('items')
        ->where('status', 'RETURNED')
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('loaner_name', 'like', "%{$search}%")
                    ->orWhere('loan_date', 'like', "%{$search}%")
                    ->orWhere('return_date', 'like', "%{$search}%")
                    ->orWhereHas('items', function ($itemQuery) use ($search) {
                        $itemQuery->where('name', 'like', "%{$search}%");
                    });
            });
        })
        ->paginate(20);

    $outgoingLoans = Loan::with('items')
        ->where('status', '!=', 'RETURNED')
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('loaner_name', 'like', "%{$search}%")
                    ->orWhere('loan_date', 'like', "%{$search}%")
                    ->orWhere('return_date', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('items', function ($itemQuery) use ($search) {
                        $itemQuery->where('name', 'like', "%{$search}%");
                    });
            });
        })
        ->paginate(20);

    return view('pages.loan', compact('incomingLoans', 'outgoingLoans'));
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
            $loan = Loan::create([
                'user_id' => $request->user_id,
                'code_loans' => $request->code_loans,
                'loan_date' => $request->loan_date,
                'return_date' => $request->return_date,
                'status' => $request->status,
                'loaner_name' => $request->loaner_name,
                'description' => $request->description,
            ]);

            foreach ($request->items as $item) {
                $itemModel = Item::findOrFail($item['item_id']);

                // Cek apakah item sedang dipinjam
                if ($itemModel->status === 'NOT READY') {
                    throw new \Exception("Item '{$itemModel->name}' sedang dipinjam dan tidak bisa dipinjam kembali.");
                }

                // Simpan ke pivot
                $loan->items()->attach($item['item_id'], [
                    'quantity' => $item['quantity'],
                ]);

                // Update status item jadi dipinjam
                $itemModel->update(['status' => 'NOT READY']);
            }

            DB::commit();
            return response()->json(['message' => 'Loan berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }




    public function exportHistory()
    {
        return Excel::download(new HistoryExport, 'loan_history.xlsx');
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
