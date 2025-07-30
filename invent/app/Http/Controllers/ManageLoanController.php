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
    try {
        $search = request('search-navbar');
        $sortBy = request('sortBy', 'loan_date');
        $sortDir = request('sortDir', 'desc');
        $allowedSorts = ['loan_date', 'code_loans', 'loaner_name', 'return_date'];

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
            ->with('items');

        if (in_array($sortBy, $allowedSorts)) {
            $myloans = $myloans->orderBy($sortBy, $sortDir);
        } else {
            $myloans = $myloans->orderBy('loan_date', 'desc');
        }

        $myloans = $myloans->paginate(20)->appends([
            'search-navbar' => $search,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
        ]);

        return view('pages.manageLoan', compact('myloans', 'sortBy', 'sortDir'));

    } catch (\Exception $e) {
        report($e); // atau Log::error($e)

        Log::error("Failed to fetch loans: " . $e->getMessage());
        return redirect()->back()->with('toast_error', 'Gagal memuat data pinjaman.Tolong coba lagi.');
    }
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
            report($e); // atau Log::error($e)

            Log::error("Failed to fetch loan {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Pinjaman tidak ditemukan atau tidak dapat dimuat'
            ], 404);
        }
    }

    public function destroy($id)
    {
        try {
            $loan = Loan::findOrFail($id);

            DB::beginTransaction();
            
            $loan->items()->each(function($item) {
                $item->status = 'READY';
                $item->save();
            });

            $loan->delete();
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pinjaman berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            DB::rollBack();
            Log::error("Failed to delete loan {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pinjaman.Tolong coba lagi.'
            ], 500);
        }
    }

    public function returnLoan(Request $request, $id)
    {
        $request->validate([
            'condition' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        try {
            $loan = Loan::with('items')->findOrFail($id);

            DB::beginTransaction();
            
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
                'success' => true,
                'message' => 'Pinjaman berhasil kembali',
                'return' => $return
            ]);

        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            DB::rollBack();
            Log::error("Failed to return loan {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pengembalian.Tolong coba lagi.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}