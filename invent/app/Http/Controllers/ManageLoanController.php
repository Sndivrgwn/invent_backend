<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Returns;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManageLoanController extends Controller
{
    public function index()
    {
        $search = request('search-navbar');
        
        $myloans = auth()->user()->loans()
            ->where('status', 'borrowed')
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('code_loans', 'like', '%'.$search.'%')
                      ->orWhere('loaner_name', 'like', '%'.$search.'%')
                      ->orWhereHas('items', function($itemQuery) use ($search) {
                          $itemQuery->where('code', 'like', '%'.$search.'%')
                                    ->orWhere('name', 'like', '%'.$search.'%');
                      });
                });
            })
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('pages.manageLoan', compact('myloans')); 
    }

    public function show($id): JsonResponse
    {
        try {
            $loan = Loan::with([
                'items.category:id,name',
                'items.location:id,name,description',
                'user:id,name',
                'items'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $loan
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to fetch loan {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Loan not found'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $loan = Loan::find($id);
        if (!$loan) {
            return response()->json(['message' => 'Loan not found'], 404);
        }

        $loan->items()->each(function($item) {
            $item->status = 'READY'; // Reset item status to READY
            $item->save();
        });

        $loan->delete();
        return response()->json(['message' => 'Loan deleted successfully']);
    }

    public function returnLoan(Request $request, $id)
{
    $request->validate([
        'condition' => 'required|string',
        'notes' => 'nullable|string'
    ]);

    $loan = Loan::with('items')->find($id);
    
    if (!$loan) {
        return response()->json(['message' => 'Loan not found'], 404);
    }

    DB::beginTransaction();
    try {
        // 1. Create return record
        $return = Returns::create([
            'return_date' => now(),
            'condition' => $request->condition,
            'notes' => $request->notes,
            'loan_id' => $loan->id
        ]);

        // 2. Update loan status
        $loan->status = 'returned';
        $loan->save();

        // 3. Update all items status to READY
        $loan->items()->update(['status' => 'READY']);

        DB::commit();

        return response()->json([
            'message' => 'Loan returned successfully',
            'return' => $return
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Failed to return loan {$id}: " . $e->getMessage());
        return response()->json([
            'message' => 'Failed to process return',
            'error' => $e->getMessage()
        ], 500);
    }
}
}