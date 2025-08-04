<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Loan;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $loans = Loan::query()->where('status', 'returned')
            ->with(['items.category', 'items.location', 'return'])
            ->orderBy('created_at', 'desc');  // Add this line to sort by newest date first

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

        // Date range filter
        if ($startDate && $endDate) {
            $loans->whereBetween('loan_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $loans->whereDate('loan_date', '>=', $startDate);
        } elseif ($endDate) {
            $loans->whereDate('loan_date', '<=', $endDate);
        }

        $loans = $loans->with('items.category')->paginate(20);

        return view('pages.history', compact('loans', 'search', 'locations', 'categories'));
    }

    public function filter(Request $request)
    {
        $loans = Loan::with(['items.location', 'items.category', 'return']) // Tambahkan 'return' di eager load
            ->where('status', 'returned')
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
            ->get()->map(function ($loan) {
                // Tambahkan encrypted_id ke setiap loan
                $loan->encrypted_id = Crypt::encryptString($loan->id);
                $loan->can_delete = auth()->user()->can('adminFunction');
                return $loan;
            });

        return response()->json($loans);
    }

    public function show($id): JsonResponse
    {
        try {
            $loan = Loan::with([
                'items.category:id,name',
                'items.location:id,name',
                'user:id,name',
                'items',
                'return'
                // Ensure items are loaded
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
                'message' => 'Pinjaman tidak ditemukan'
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
                    'message' => 'Tidak dapat menghapus pinjaman aktif'
                ], 403);
            }

            $loan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pinjaman berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            report($e); // atau Log::error($e)

            Log::error("Failed to delete loan {$id}: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pinjaman'
            ], 500);
        }
    }
}