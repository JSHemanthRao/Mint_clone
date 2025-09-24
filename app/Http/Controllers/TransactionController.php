<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        try {
            $accountIds = Auth::user()->accounts()->pluck('id');

            $transactions = Transaction::with(['category', 'account'])
                ->whereIn('account_id', $accountIds)
                ->orderBy('date', 'desc')
                ->get();

            return response()->json($transactions);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function display()
    {
        $categories = Category::all();
        $accounts = Account::where('user_id', Auth::id())->get();
        return view('Transactions', compact('categories', 'accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date'
        ]);

        $account = Account::where('id', $validated['account_id'])
                          ->where('user_id', Auth::id())
                          ->first();

        if (!$account) {
            return response()->json(['error' => 'Account not found or unauthorized'], 403);
        }

        DB::beginTransaction();
        try {
            // Deduct from account balance
            $account->balance -= $validated['amount'];
            $account->save();

            $validated['user_id'] = Auth::id();
            $transaction = Transaction::create($validated);
            $transaction->load(['category', 'account']);

            DB::commit();
            return response()->json($transaction, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $accountIds = Auth::user()->accounts()->pluck('id');

        $transaction = Transaction::with(['category', 'account'])
            ->whereIn('account_id', $accountIds)
            ->where('id', $id)
            ->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        return response()->json($transaction);
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $accountIds = Auth::user()->accounts()->pluck('id');
        if (!$accountIds->contains($transaction->account_id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date'
        ]);

        DB::beginTransaction();
        try {
            // Adjust account balance: remove old transaction amount and apply new amount
            $oldAccount = $transaction->account;
            $oldAccount->balance += $transaction->amount;
            $oldAccount->save();

            $newAccount = Account::find($validated['account_id']);
            $newAccount->balance -= $validated['amount'];
            $newAccount->save();

            $transaction->update($validated);
            DB::commit();

            $transaction->load(['category', 'account']);
            return response()->json($transaction, 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) return response()->json(['message' => 'Transaction not found'], 404);

        $accountIds = Auth::user()->accounts()->pluck('id');
        if (!$accountIds->contains($transaction->account_id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        DB::beginTransaction();
        try {
            // Refund amount back to account
            $account = $transaction->account;
            $account->balance += $transaction->amount;
            $account->save();

            $transaction->delete();
            DB::commit();

            return response()->json(['message' => 'Transaction deleted successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
