<?php

namespace App\Http\Controllers;

use App\Exports\CategoryExport;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Exception;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function index()
    {
        try {
            $categories = Category::with(['items.loans'])->get()
                ->map(function ($category) {
                    // Ambil hanya item dengan kondisi GOOD
                    $goodItems = $category->items->filter(function ($item) {
                        return $item->condition === 'GOOD';
                    })->unique('id');

                    $itemsCount = $goodItems->count();

                    // Hitung loans hanya dari item kondisi GOOD
                    $loanCount = $goodItems->sum(function ($item) {
                        return $item->loans->where('status', 'borrowed')->count();
                    });

                    $availableCount = $itemsCount - $loanCount;

                    // Group berdasarkan type item GOOD
                    $allTypes = $category->items->pluck('type')->unique();

                    $typeSummaries = $allTypes->map(function ($type) use ($goodItems) {
                        $itemsOfType = $goodItems->where('type', $type);

                        $total = $itemsOfType->count();
                        $loaned = $itemsOfType->sum(function ($item) {
                            return $item->loans->where('status', 'borrowed')->count();
                        });
                        $available = $total - $loaned;

                        return (object)[
                            'type' => $type,
                            'quantity' => $total,
                            'available' => $available,
                            'loaned' => $loaned,
                            'low_stock' => $available < 3 ? 'Yes' : 'No',
                        ];
                    });


                    $category->items_count = $itemsCount;
                    $category->loan_count = $loanCount;
                    $category->available_count = $availableCount;
                    $category->low_stock = $availableCount < 3 ? 'Yes' : 'No';
                    $category->type_summaries = $typeSummaries->values();

                    return $category;
                });

            return view('pages.analytics', compact('categories'));
        } catch (Exception $e) {
            Log::error('Error in AnalyticsController@index: ' . $e->getMessage());
            report($e);

            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Gagal memuat data analitik. Tolong coba lagi.'
            ]);
        }
    }



    /**
     * Export categories report
     */
    public function export()
    {
        try {
            return Excel::download(new CategoryExport, 'categories_report_' . now()->format('Ymd_His') . '.xlsx');
        } catch (Exception $e) {
            Log::error('Error in AnalyticsController@export: ' . $e->getMessage());
            report($e); // atau Log::error($e)

            return redirect()->back()->with('error', 'Gagal menghasilkan ekspor.Tolong coba lagi.');
        }
    }

    /**
     * Store a new category
     */
    // In store method
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'nullable|string',
            ], [
                'name.required' => 'Nama lokasi wajib diisi.',
                'name.unique' => 'Nama lokasi sudah digunakan, silakan pilih nama lain.',
            ]);

            Category::create($validated);

            return redirect()->route('analytics')->with('toast', [
                'type' => 'success',
                'message' => 'Kategori berhasil dibuat!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            report($e); // atau Log::error($e)
            Log::error('Validation error in AnalyticsController@store: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('toast', [
                    'type' => 'error',
                    'message' => $e->getMessage(),
                ]);
        } catch (Exception $e) {
            Log::error('Error in AnalyticsController@store: ' . $e->getMessage());
            report($e); // atau Log::error($e)

            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Gagal membuat kategori.Tolong coba lagi.'
            ]);
        }
    }

    // In destroy method
    public function destroy(string $id)
    {
        try {
            $category = Category::findOrFail($id);

            \DB::transaction(function () use ($category) {
                $category->items()->each(function ($item) {
                    $item->loans()->detach();
                    $item->delete();
                });
                $category->delete();
            });

            return response()->json([
                'success' => true,
                'toast' => [
                    'type' => 'success',
                    'message' => 'Kategori dan semua item terkait berhasil dihapus!'
                ],
                'reload' => true
            ]);
        } catch (Exception $e) {
            Log::error('Error in AnalyticsController@destroy: ' . $e->getMessage());
            report($e); // atau Log::error($e)
            return response()->json([
                'success' => false,
                'toast' => [
                    'type' => 'error',
                    'message' => 'Gagal Menghapus Kategori'
                ]
            ], 500);
        }
    }

    /**
     * Update a category
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $id,
                'description' => 'nullable|string|max:500',
            ]);

            $category = Category::findOrFail($id);
            $category->update($validated);

            return response()->json([
                'success' => true,
                'toast' => [
                    'type' => 'success',
                    'message' => 'Kategori berhasil diperbarui!'
                ],
                'reload' => true
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'toast' => [
                    'type' => 'error',
                    'message' => 'Validasi gagal: ' . implode(' ', $e->validator->errors()->all())
                ]
            ], 422);
        } catch (Exception $e) {
            Log::error('Error in AnalyticsController@update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'toast' => [
                    'type' => 'error',
                    'message' => 'Gagal memperbarui kategori'
                ]
            ], 500);
        }
    }

    /**
     * Delete a category and its related items
     */
}
