<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    // For web view (dashboard)
    public function dashboard()
    {
        $accounts = Account::where('user_id', Auth::id())->get();
        return view('accounts.index', compact('accounts'));
    }

    // For API
    public function index()
    {
        return response()->json(Account::all(), 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // If user_id not provided, set the logged-in user
        if (!isset($data['user_id'])) {
            $data['user_id'] = Auth::id();
        }

        $validator = Validator::make($data, [
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

        // If the request expects JSON (API), return JSON
        if ($request->expectsJson()) {
            return response()->json($account, 201);
        }

        // Otherwise, redirect back for web
        return redirect()->route('accounts.index')->with('success', 'Account created successfully');
    }

}