<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $loans = Loan::query()->where('status', 'returned')
            ->with(['items.category', 'items.location', 'return']);

        $locations = Location::all();
        $categories = Category::all();

        if ($search) {
            $loans->where(function ($query) use ($search) {
                $query->where('code_loans', 'like', "%{$search}%")
                    ->orWhere('loaner_name', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('loan_date', 'like', "%{$search}%")
                    ->orWhere('return_date', 'like', "%{$search}%")
                    ->orWhereHas('items', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('type', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%")
                            ->orWhereHas('category', function ($query) use ($search) {
                                $query->where('name', 'like', "%{$search}%");
                            });
                    })
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Tambahkan filter tanggal
        if ($startDate) {
            $loans->whereDate('loan_date', '>=', $startDate);
        }

        if ($endDate) {
            $loans->whereDate('loan_date', '<=', $endDate);
        }

        $loans = $loans->with('items.category')->paginate(20);

        return view('pages.history', compact('loans', 'search', 'locations', 'categories'));
    }


   public function filter(Request $request)
{
    $loans = Loan::with(['items.location', 'items.category'])
        ->where('status', 'returned') // Tambahkan ini untuk filter hanya returned
        ->whereHas('items', function ($query) use ($request) {
            $query->when($request->brand, fn($q) => $q->where('brand', $request->brand))
                ->when($request->type, fn($q) => $q->where('type', $request->type))
                ->when($request->condition, fn($q) => $q->where('condition', $request->condition))
                ->when($request->category, function ($q) use ($request) {
                    $q->whereHas('category', fn($q) => $q->where('name', $request->category));
                })
                ->when($request->location, function ($q) use ($request) {
                    $q->whereHas('location', fn($q) => $q->where('description', $request->location));
                });
        })
        ->when($request->status, fn($q) => $q->where('status', $request->status))
        ->get();

    return response()->json($loans);
}

    public function show($id): JsonResponse
    {
        try {
            $loan = Loan::with([
                'items.category:id,name',
                'items.location:id,name',
                'user:id,name',
                'items' ,
                'return'
                // Ensure items are loaded
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

    public function destroy($id): JsonResponse
    {
        try {
            $loan = Loan::findOrFail($id);

            // Check if loan can be deleted (you might add business logic here)
            if ($loan->status === 'borrowed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete an active loan'
                ], 403);
            }

            $loan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Loan deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to delete loan {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete loan'
            ], 500);
        }
    }
}
