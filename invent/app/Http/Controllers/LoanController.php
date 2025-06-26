<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\HistoryExport;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Crypt;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    try {
        $search = $request->input('search-navbar');

        $sortByIncoming = $request->input('sortByIncoming', 'loan_date');
        $sortDirIncoming = $request->input('sortDirIncoming', 'desc');

        $sortByOutgoing = $request->input('sortByOutgoing', 'loan_date');
        $sortDirOutgoing = $request->input('sortDirOutgoing', 'desc');

        $allowedSorts = ['loaner_name', 'loan_date', 'return_date', 'status'];

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
            });

        if (in_array($sortByIncoming, $allowedSorts)) {
            $incomingLoans->orderBy($sortByIncoming, $sortDirIncoming);
        }

        $incomingLoans = $incomingLoans->paginate(20)->appends([
            'search-navbar' => $search,
            'sortByIncoming' => $sortByIncoming,
            'sortDirIncoming' => $sortDirIncoming,
            'sortByOutgoing' => $sortByOutgoing,
            'sortDirOutgoing' => $sortDirOutgoing,
        ]);

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
            });

        if (in_array($sortByOutgoing, $allowedSorts)) {
            $outgoingLoans->orderBy($sortByOutgoing, $sortDirOutgoing);
        }

        $outgoingLoans = $outgoingLoans->paginate(20)->appends([
            'search-navbar' => $search,
            'sortByIncoming' => $sortByIncoming,
            'sortDirIncoming' => $sortDirIncoming,
            'sortByOutgoing' => $sortByOutgoing,
            'sortDirOutgoing' => $sortDirOutgoing,
        ]);

        return view('pages.loan', compact(
            'incomingLoans', 'outgoingLoans',
            'sortByIncoming', 'sortDirIncoming',
            'sortByOutgoing', 'sortDirOutgoing',
        ));

    } catch (\Exception $e) {
        report($e); // atau Log::error($e)

        Log::error('Error fetching loans: ' . $e->getMessage());
        return redirect()->back()->with('toast_error', 'Failed to load loan data. Please try again.');
    }
}


    /**
     * Store a newly created resource in storage.
     */
    
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

            if ($itemModel->status === 'NOT READY') {
                throw new \Exception("Item '{$itemModel->name}' (SN: {$itemModel->code}) is currently borrowed and cannot be loaned again.");
            }

            $loan->items()->attach($item['item_id'], [
                'quantity' => $item['quantity'],
            ]);

            $itemModel->update(['status' => 'NOT READY']);
        }

        DB::commit();
        return response()->json([
            'message' => 'Loan successfully created!'
        ], 201);
    } catch (\Exception $e) {
        report($e); // atau Log::error($e)
        Log::error('Loan creation failed: ' . $e->getMessage());
        DB::rollBack();
        return response()->json([
            'message' => $e->getMessage()
        ], 400);
    }
}

    public function exportHistory()
    {
        try {
            return Excel::download(new HistoryExport, 'loan_history_' . date('Ymd_His') . '.xlsx');
        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            Log::error('Export failed: ' . $e->getMessage());
            return back()->with('toast_error', 'Failed to generate export. Please try again.');
        }
    }

    public function printPdf(string $id)
{
    try {
        $loanId = Crypt::decryptString($id);
        $loan = Loan::with(['items.category', 'user'])->findOrFail($loanId);

        $pdf = Pdf::loadView('print.loan-detail', compact('loan'))
                  ->setPaper('A4', 'portrait');

        return $pdf->stream('loan_form_' . $loan->code_loans . '.pdf');

    } catch (\Exception $e) {
        report($e); // atau Log::error($e)

        Log::error('Loan PDF stream failed: ' . $e->getMessage());
        return redirect()->back()->with('toast_error', 'Gagal menampilkan PDF');
    }
}


    public function search(Request $request)
    {
        try {
            $keyword = $request->query('q');
            
            if (empty($keyword) || strlen($keyword) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search keyword must be at least 2 characters'
                ], 400);
            }

            $items = Item::where('status', 'READY')
                ->where(function($query) use ($keyword) {
                    $query->where('code', 'LIKE', "%$keyword%")
                        ->orWhere('name', 'LIKE', "%$keyword%")
                        ->orWhere('brand', 'LIKE', "%$keyword%")
                        ->orWhere('type', 'LIKE', "%$keyword%");
                })
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $items
            ]);

        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            Log::error('Search failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Search failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $loan = Loan::with(['user', 'items', 'return'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $loan
            ]);

        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            Log::error('Failed to fetch loan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Loan not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
        'loaner_name' => 'sometimes|required|string|max:100',
        'return_date' => [
            'sometimes',
            'required',
            'date',
            function ($attribute, $value, $fail) use ($request, $id) {
                $loan = Loan::find($id);
                if (!$loan) {
                    $fail('Loan not found');
                    return;
                }
                
                $today = now()->format('Y-m-d');
                $maxDate = date('Y-m-d', strtotime($loan->loan_date . ' +14 days'));
                
                if ($value < $today) {
                    $fail('Return date cannot be before today');
                }
                
                if ($value > $maxDate) {
                    $fail('Return date cannot be more than 2 weeks from loan date');
                }
            }
        ],
        'description' => 'nullable|string|max:500',
    ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $loan = Loan::findOrFail($id);

            if ($loan->status === 'RETURNED') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update a returned loan'
                ], 403);
            }

            // Only update fields that are present in the request
            $updateData = $request->only([
                'loaner_name',
                'return_date',
                'description',
                'status'
            ]);

            $loan->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Loan updated successfully!',
                'data' => $loan
            ]);

        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            Log::error('Loan update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update loan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $loan = Loan::findOrFail($id);

            // Only allow deletion if loan is not already returned
            if ($loan->status === 'RETURNED') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete a returned loan'
                ], 403);
            }

            // Reset item statuses
            $loan->items()->each(function ($item) {
                $item->update(['status' => 'READY']);
            });

            $loan->delete();

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Loan deleted successfully!'
            ]);

        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            DB::rollBack();
            Log::error('Loan deletion failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete loan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}