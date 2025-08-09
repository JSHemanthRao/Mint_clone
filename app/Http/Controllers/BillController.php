<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;

class BillController extends Controller
{
    
    public function index()
    {
        $bills = Bill::all();
        return response()->json($bills, 200);
    }

    
    public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        "name" => "required|string|max:200",
        'amount' => 'required|numeric',
        'due_date' => 'required|date',
    ]);

    $bill = Bill::create($validated);

    return response()->json([
        'message' => 'Bill created successfully',
        'data' => $bill
    ], 201);
}


    
    public function show($id)
    {
        $bill = Bill::findOrFail($id);
        return response()->json($bill, 200);
    }


    public function update(Request $request, $id)
    {
        $bill = Bill::find($id);
        if (!$bill){
            return response()->json(['message'=>'Bill not found'], 404);
        }
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => "required|string|max:200",
            'amount' => 'required|numeric',
            'due_date' => 'required|date',
        ]);

        
        $bill->update($validated);

        return response()->json([
            'message' => 'Bill updated successfully',
            'data' => $bill
        ], 200);
    }

    
    public function destroy($id)
    {
        $bill = Bill::findOrFail($id);
        $bill->delete();

        return response()->json([
            'message' => 'Bill deleted successfully'
        ], 200);
    }
}
