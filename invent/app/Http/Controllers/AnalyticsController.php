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
                // Pastikan item yang sama tidak dihitung lebih dari sekali
                $uniqueItems = $category->items->unique('id');

                $itemsCount = $uniqueItems->count();

                // Hitung loans hanya dari item unik
                $loanCount = $uniqueItems->sum(function ($item) {
                    return $item->loans->where('status', 'borrowed')->count();
                });

                $availableCount = $itemsCount - $loanCount;

                // Group berdasarkan type item unik
                $typeSummaries = $uniqueItems->groupBy('type')->map(function ($items) {
                    $total = $items->count();

                    $loaned = $items->sum(function ($item) {
                        return $item->loans->where('status', 'borrowed')->count();
                    });

                    $available = $total - $loaned;

                    return (object)[
                        'type' => $items->first()->type,
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
        return redirect()->back()->with('toast', [
            'type' => 'error',
            'message' => 'Failed to load analytics data. Please try again.'
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
            return redirect()->back()->with('error', 'Failed to generate export. Please try again.');
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
            'description' => 'nullable|string|max:500',
        ]);

        Category::create($validated);

        return redirect()->route('analytics')->with('toast', [
            'type' => 'success',
            'message' => 'Category created successfully!'
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput()
            ->with('toast', [
                'type' => 'error',
                'message' => 'Validation failed. Please check your input.'
            ]);
    } catch (Exception $e) {
        Log::error('Error in AnalyticsController@store: ' . $e->getMessage());
        return redirect()->back()->with('toast', [
            'type' => 'error',
            'message' => 'Failed to create category. Please try again.'
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
                'message' => 'Category and all related items deleted successfully!'
            ],
            'reload' => true
        ]);

    } catch (Exception $e) {
        Log::error('Error in AnalyticsController@destroy: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'toast' => [
                'type' => 'error',
                'message' => 'Failed to delete category'
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
                'message' => 'Category updated successfully!'
            ],
            'reload' => true
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'toast' => [
                'type' => 'error',
                'message' => 'Validation failed: ' . implode(' ', $e->validator->errors()->all())
            ]
        ], 422);
        
    } catch (Exception $e) {
        Log::error('Error in AnalyticsController@update: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'toast' => [
                'type' => 'error',
                'message' => 'Failed to update category'
            ]
        ], 500);
    }
}

    /**
     * Delete a category and its related items
     */
    
}