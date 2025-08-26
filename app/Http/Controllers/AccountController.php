<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AccountController extends Controller
{
    public function index()
    {
        return response()->json(Account::where('user_id', Auth::id())->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'balance' => 'required|numeric'
        ]);

        // Authenticate user from JWT
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Attach the user_id properly
        $validated['user_id'] = $user->id;

        $account = Account::create($validated);

        return response()->json($account, 201);
    }


    public function show($id)
    {
        $account = Account::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return response()->json($account);
    }

    public function edit($id)
    {
        $account = Account::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('edit', compact('account'));
    }

    public function update(Request $request, $id)
    {
        $account = Account::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'balance' => 'required|numeric'
        ]);

        $validated['user_id'] = Auth::id();

        $account->update($validated);
        return response()->json($account, 200);
    }

    public function destroy($id)
    {
        $account = Account::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $account->delete();
        return response()->json(['message' => 'Account deleted']);
    }
    public function deposit(Request $request, $id)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        // Ensure the account belongs to the logged-in user
        $account = Account::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $account->balance += $validated['amount'];
        $account->save();

        return response()->json([
            'message' => 'Amount deposited successfully',
            'account' => $account
        ]);
    }

    public function withdraw(Request $request, $id)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $account = Account::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($validated['amount'] > $account->balance) {
            return response()->json(['error' => 'Insufficient balance'], 400);
        }

        $account->balance -= $validated['amount'];
        $account->save();

        return response()->json([
            'message' => 'Amount withdrawn successfully',
            'account' => $account
        ]);
    }
}
