<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget; 

class BudgetController extends Controller
{
    public function index()
    {
        return response()->json(Budget::all()); 
    }


    public function store(Request $request)
    {
       
        $validated = $request->validate([
            'user_id' => 'required|numeric',
            'category_id'=> 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        
        $budget = Budget::create($validated);

        return response()->json($budget, 201);
    }

    public function show(string $id)
    {
        $budget = Budget::findOrFail($id);
        return response()->json($budget);
    }


    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|numeric',
            'category_id'=> 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        $budget = Budget::findOrFail($id);
        $budget->update($validated);

        return response()->json($budget);
    }

    public function destroy(string $id)
    {
        $budget = Budget::find($id);
        $budget->delete();

        return response()->json(null, 204);
    }
}
