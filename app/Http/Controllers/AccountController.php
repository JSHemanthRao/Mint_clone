<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    // Display a listing of accounts
    public function index()
    {
        return response()->json(Account::all());
    }

    // Store a newly created account
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'plaid_item_id' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'balance' => 'required|numeric',
        ]);

        $account = Account::create($validated);

        return response()->json($account, 201);
    }

    // Display a single account
    public function show($id)
    {
        $account = Account::findOrFail($id);
        return response()->json($account);
    }

    // Update an account
    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $validated = $request->validate([
            'plaid_item_id' => 'nullable|string|max:255',
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|max:100',
            'balance' => 'sometimes|required|numeric',
        ]);

        $account->update($validated);

        return response()->json($account);
    }

    // Delete an account
    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return response()->json(['message' => 'Account deleted successfully']);
    }
}
