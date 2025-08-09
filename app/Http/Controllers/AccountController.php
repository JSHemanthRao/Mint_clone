<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function index()
    {
        return response()->json(Account::all(), 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
            'plaid_item_id' => 'nullable|string',
            'name' => 'required|string',
            'type' => 'required|string',
            'balance' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $account = Account::create($validator->validated());
        return response()->json($account, 201);
    }

    public function show($id)
    {
        $account = Account::find($id);

        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        return response()->json($account, 200);
    }

    public function update(Request $request, $id)
    {
        $account = Account::find($id);

        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id',
            'plaid_item_id' => 'nullable|string',
            'name' => 'required|string',
            'type' => 'required|string',
            'balance' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $account->update($validator->validated());

        return response()->json($account, 200);
    }

    public function destroy($id)
    {
        $account = Account::find($id);

        if (!$account) {
            return response()->json(['message' => 'Account not found'], 404);
        }

        $account->delete();
        return response()->json(['message' => 'Account deleted successfully'], 200);
    }
}
