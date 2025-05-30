<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Returns;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Returns::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'return_date' => 'required|date',
            'condition' => 'required|in:baik,rusak,hilang',
            'notes' => 'nullable|string',
        ]);

        $data = Returns::create($request->all());
        return response()->json(['message' => 'Return created', 'data' => $data], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Returns::with('loan')->find($id);
        if (!$data) {
            return response()->json(['message' => 'Return not found'], 404);
        }

        return response()->json($data, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = Returns::find($id);
        if (!$data) {
            return response()->json(['message' => 'Return not found'], 404);
        }

        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'return_date' => 'required|date',
            'condition' => 'required|in:baik,rusak,hilang',
            'notes' => 'nullable|string',
        ]);

        $data->update($request->all());
        return response()->json(['message' => 'Return updated', 'data' => $data], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Returns::find($id);
        if (!$data) {
            return response()->json(['message' => 'Return not found'], 404);
        }

        $data->delete();
        return response()->json(['message' => 'Return deleted'], 200);
    }
}
