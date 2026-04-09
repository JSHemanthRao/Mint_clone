<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Budget;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    //
    public function index()
    {
        $budgets = Budget::with('category')->where('user_id', Auth::id())->get();
        return response()->json($budgets);
    }


    public function display()
    {
        $categories = Category::all();
        return view('Budgets', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric',
        ]);

        $validated['user_id'] = Auth::id();
        $budget = Budget::create($validated);


        return response()->json($budget->load('category'), 201);
    }


    public function show($id)
    {
        $budget = Budget::with('category')->find($id);
        if (!$budget) {
            return response()->json(['message' => 'Budget not found'], 404);
        }
        return response()->json($budget);
    }

    public function update(Request $request, $id)
    {
        $budget = Budget::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$budget) {
            return response()->json(['message' => 'Budget not found or unauthorized'], 404);
        }

        $validated = $request->validate([
            'category_id' => 'exists:categories,id',
            'amount' => 'numeric',
        ]);

        $budget->update($validated);
        return response()->json($budget);
    }

    public function destroy($id)
    {
        $budget = Budget::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$budget) {
            return response()->json(['message' => 'Budget not found or unauthorized'], 404);
        }
        $budget->delete();
        return response()->json(['message' => 'Budget deleted successfully']);
    }
}