<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal; 

class GoalController extends Controller
{
    public function index()
    {
        return response()->json(Goal::all()); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string',
            'target_amount' => 'required|numeric',
            'current_amount' => 'required|numeric'
        ]);

        $goal = Goal::create($request->all());
        return response()->json($goal, 201);
    }

    public function update(Request $request, string $id)
    {
        $goal = Goal::find($id);

        if (!$goal) {
            return response()->json(['message' => 'Goal not found'], 404);
        }

        $request->validate([
            'user_id' => 'required|integer',
            'name' => 'required|string',
            'target_amount' => 'required|numeric',
            'current_amount' => 'required|numeric'
        ]);

        $goal->update($request->all());

        return response()->json($goal, 200);
    }

    public function destroy(string $id)
    {
        $goal = Goal::find($id);

        if (!$goal) {
            return response()->json(['message' => 'Goal not found'], 404);
        }

        $goal->delete();

        return response()->json(['message' => 'Goal deleted successfully'], 200);
    }
}
