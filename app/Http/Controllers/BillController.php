<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillController extends Controller
{
    
    public function index(Request $request)
    {
        $bills = $request->user()->bills()->latest()->get();

        return response()->json($bills);
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
        ]);

        $bill = $request->user()->bills()->create($validated);

        return response()->json([
            'message' => 'Bill saved successfully',
            'bill' => $bill
        ], 201);
    }

    
    public function show(Request $request, $id)
    {
        $bill = $request->user()->bills()->findOrFail($id);

        return response()->json($bill);
    }

    
    public function update(Request $request, $id)
    {
        $bill = $request->user()->bills()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'amount' => 'sometimes|required|numeric',
            'due_date' => 'sometimes|required|date',
        ]);

        $bill->update($validated);

        return response()->json([
            'message' => 'Bill updated successfully',
            'bill' => $bill
        ]);
    }

        public function destroy(Request $request, $id)
    {
        $bill = $request->user()->bills()->findOrFail($id);
        $bill->delete();

        return response()->json(['message' => 'Bill deleted successfully']);
    }
}
