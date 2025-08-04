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
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;

class LoanController extends Controller
{
    // Cache configuration
    const CACHE_TTL = 1800; // 30 minutes
    const CACHE_KEY_PREFIX = 'loans_';

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
            $perPage = 20;

            // Current page
            $currentPage = LengthAwarePaginator::resolveCurrentPage();

            // Generate cache key (TIDAK pakai page)
            $cacheKey = self::CACHE_KEY_PREFIX . 'index_' . md5(json_encode([
                'search' => $search,
                'sortByIncoming' => $sortByIncoming,
                'sortDirIncoming' => $sortDirIncoming,
                'sortByOutgoing' => $sortByOutgoing,
                'sortDirOutgoing' => $sortDirOutgoing,
            ]));

            // Ambil data cache tanpa pagination
            $cachedData = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($search, $sortByIncoming, $sortDirIncoming, $sortByOutgoing, $sortDirOutgoing) {
                $allowedSorts = ['loaner_name', 'loan_date', 'return_date', 'status'];

                $incomingQuery = Loan::with('items')->where('status', 'RETURNED');
                $outgoingQuery = Loan::with('items')->where('status', '!=', 'RETURNED');

                if ($search) {
                    $incomingQuery->where(function ($q) use ($search) {
                        $q->where('loaner_name', 'like', "%{$search}%")
                            ->orWhere('loan_date', 'like', "%{$search}%")
                            ->orWhere('return_date', 'like', "%{$search}%")
                            ->orWhereHas('items', function ($itemQuery) use ($search) {
                                $itemQuery->where('name', 'like', "%{$search}%");
                            });
                    });

                    $outgoingQuery->where(function ($q) use ($search) {
                        $q->where('loaner_name', 'like', "%{$search}%")
                            ->orWhere('loan_date', 'like', "%{$search}%")
                            ->orWhere('return_date', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%")
                            ->orWhereHas('items', function ($itemQuery) use ($search) {
                                $itemQuery->where('name', 'like', "%{$search}%");
                            });
                    });
                }

                if (in_array($sortByIncoming, $allowedSorts)) {
                    $incomingQuery->orderBy($sortByIncoming, $sortDirIncoming);
                }

                if (in_array($sortByOutgoing, $allowedSorts)) {
                    $outgoingQuery->orderBy($sortByOutgoing, $sortDirOutgoing);
                }

                return [
                    'incomingLoans' => $incomingQuery->get(),
                    'outgoingLoans' => $outgoingQuery->get(),
                ];
            });

            // Manual pagination setelah ambil dari cache
            $incomingLoans = new LengthAwarePaginator(
                collect($cachedData['incomingLoans'])->forPage($currentPage, $perPage)->values(),
                count($cachedData['incomingLoans']),
                $perPage,
                $currentPage,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                    'pageName' => 'incoming_page',
                ]
            );

            $outgoingLoans = new LengthAwarePaginator(
                collect($cachedData['outgoingLoans'])->forPage($currentPage, $perPage)->values(),
                count($cachedData['outgoingLoans']),
                $perPage,
                $currentPage,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                    'pageName' => 'outgoing_page',
                ]
            );

            return view('pages.loan', [
                'incomingLoans' => $incomingLoans,
                'outgoingLoans' => $outgoingLoans,
                'sortByIncoming' => $sortByIncoming,
                'sortDirIncoming' => $sortDirIncoming,
                'sortByOutgoing' => $sortByOutgoing,
                'sortDirOutgoing' => $sortDirOutgoing,
            ]);
        } catch (\Exception $e) {
            report($e);
            Log::error('Error fetching loans: ' . $e->getMessage());
            return redirect()->back()->with('toast_error', 'Gagal memuat data pinjaman. Tolong coba lagi.');
        }
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

                if ($itemModel->status === 'NOT READY') {
                    throw new \Exception("Item '{$itemModel->name}' (SN: {$itemModel->code}) Sedang Dipinjam Tidak Dapat Dipinjam Kembali");
                }

                $loan->items()->attach($item['item_id'], [
                    'quantity' => $item['quantity'],
                ]);

                $itemModel->update(['status' => 'NOT READY']);
            }

            DB::commit();

            // Clear relevant caches
            $this->clearLoansCache();

            return response()->json([
                'message' => 'Pinjaman berhasil dibuat!'
            ], 201);
        } catch (\Exception $e) {
            report($e);
            Log::error('Loan creation failed: ' . $e->getMessage());
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function exportHistory(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        return Excel::download(new HistoryExport($startDate, $endDate), 'loan_history_' . date('Ymd_His') . '.xlsx');
    }

    public function printPdf(string $id)
    {
        try {
            $loanId = Crypt::decryptString($id);
            $cacheKey = self::CACHE_KEY_PREFIX . 'pdf_' . $loanId;

            $pdf = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($loanId) {
                $loan = Loan::with(['items.category', 'user'])->findOrFail($loanId);
                return Pdf::loadView('print.loan-detail', compact('loan'))
                    ->setPaper('A4', 'portrait');
            });

            return $pdf->stream('loan_form_' . $loanId . '.pdf');
        } catch (\Exception $e) {
            report($e);
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
                    'message' => 'Kata kunci pencarian harus minimal 2 karakter'
                ], 400);
            }

            $cacheKey = self::CACHE_KEY_PREFIX . 'search_' . md5($keyword);

            $items = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($keyword) {
                return Item::where('status', 'READY')
                    ->where(function ($query) use ($keyword) {
                        $query->where('code', 'LIKE', "%$keyword%")
                            ->orWhere('name', 'LIKE', "%$keyword%")
                            ->orWhere('brand', 'LIKE', "%$keyword%")
                            ->orWhere('type', 'LIKE', "%$keyword%");
                    })
                    ->limit(10)
                    ->get();
            });

            return response()->json([
                'success' => true,
                'data' => $items
            ]);
        } catch (\Exception $e) {
            report($e);
            Log::error('Search failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Pencarian gagal.Tolong coba lagi.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $cacheKey = self::CACHE_KEY_PREFIX . 'show_' . $id;

            $loan = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($id) {
                return Loan::with(['user', 'items', 'return'])->findOrFail($id);
            });

            return response()->json([
                'success' => true,
                'data' => $loan
            ]);
        } catch (\Exception $e) {
            report($e);
            Log::error('Failed to fetch loan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Pinjaman tidak ditemukan'
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
                        $fail('Pinjaman tidak ditemukan');
                        return;
                    }

                    $today = now()->format('Y-m-d');
                    $maxDate = date('Y-m-d', strtotime($loan->loan_date . ' +14 days'));

                    if ($value < $today) {
                        $fail('Tanggal pengembalian tidak bisa sebelum hari ini');
                    }

                    if ($value > $maxDate) {
                        $fail('Tanggal pengembalian tidak bisa lebih dari 2 minggu dari tanggal pinjaman');
                    }
                }
            ],
            'description' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $loan = Loan::findOrFail($id);

            if ($loan->status === 'RETURNED') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat memperbarui pinjaman yang dikembalikan'
                ], 403);
            }

            $updateData = $request->only([
                'loaner_name',
                'return_date',
                'description',
                'status'
            ]);

            $loan->update($updateData);

            // Clear relevant caches
            $this->clearLoanCache($id);
            $this->clearLoansCache();

            return response()->json([
                'success' => true,
                'message' => 'Pinjaman berhasil diperbarui!',
                'data' => $loan
            ]);
        } catch (\Exception $e) {
            report($e);
            Log::error('Loan update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui pinjaman',
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

            if ($loan->status === 'RETURNED') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus pinjaman yang dikembalikan'
                ], 403);
            }

            $loan->items()->each(function ($item) {
                $item->update(['status' => 'READY']);
            });

            $loan->delete();

            DB::commit();

            // Clear relevant caches
            $this->clearLoanCache($id);
            $this->clearLoansCache();

            return response()->json([
                'success' => true,
                'message' => 'Pinjaman berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            Log::error('Loan deletion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'gagal Menghapus Pinjaman',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all loans cache
     */
    protected function clearLoansCache()
    {
        $keys = Cache::getStore()->getRedis()->keys(self::CACHE_KEY_PREFIX . '*');
        foreach ($keys as $key) {
            // Remove prefix from key
            $key = str_replace(config('database.redis.options.prefix'), '', $key);
            Cache::forget($key);
        }
    }

    /**
     * Clear specific loan cache
     */
    protected function clearLoanCache($id)
    {
        Cache::forget(self::CACHE_KEY_PREFIX . 'show_' . $id);
        Cache::forget(self::CACHE_KEY_PREFIX . 'pdf_' . $id);
    }

    /**
     * Clear all caches when loans are modified (callable from other controllers)
     */
    public static function clearAllLoansCache()
    {
        (new self)->clearLoansCache();
    }
}
