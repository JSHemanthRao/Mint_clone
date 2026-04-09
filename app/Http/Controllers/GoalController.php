<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use Illuminate\Support\Facades\Auth;
class GoalController extends Controller
{
    public function index()
    {
        return response()->json(Goal::where('user_id', Auth::id())->get());
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_amount' => 'required|numeric',
            'current_amount' => 'numeric',
            'due_date' => 'required|date',
        ]);

        $validated['user_id'] = Auth::id();
        $goal = Goal::create($validated);
        return response()->json($goal, 201);
    }

    public function show($id)
    {
        $goal = Goal::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$goal) {
            return response()->json(['message' => 'Goal not found or unauthorized'], 404);
        }
        return response()->json($goal);
    }

    public function update(Request $request, $id)
    {
        $goal = Goal::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$goal) {
            return response()->json(['message' => 'Goal not found or unauthorized'], 404);
        }

        $validated = $request->validate([
            'name' => 'string|max:255',
            'target_amount' => 'numeric',
            'current_amount' => 'numeric',
            'due_date' => 'date',
        ]);


        $goal->update($validated);
        return response()->json($goal);
    }

    public function destroy($id)
    {
        $goal = Goal::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$goal) {
            return response()->json(['message' => 'Goal not found or unauthorized'], 404);
        }
        $goal->delete();
        return response()->json(['message' => 'Goal deleted successfully']);
    }
}