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
            $accountIds = Account::where('user_id', Auth::id())->pluck('id');

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
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'type' => 'required|in:income,expense'
        ]);

        $account = Account::where('id', $validated['account_id'])
                          ->where('user_id', Auth::id())
                          ->first();

        if (!$account) {
            return response()->json(['error' => 'Account not found or unauthorized'], 403);
        }

        DB::beginTransaction();
        try {
            // Adjust balance based on type
            if ($validated['type'] === 'expense') {
                $account->balance -= abs($validated['amount']);
            } elseif ($validated['type'] === 'income') {
                $account->balance += abs($validated['amount']);
            }
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
        $accountIds = Account::where('user_id', Auth::id())->pluck('id');

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

        $accountIds = Account::where('user_id', Auth::id())->pluck('id');
        if (!$accountIds->contains($transaction->account_id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'type' => 'required|in:income,expense'
        ]);

        DB::beginTransaction();
        try {
            // Reverse old transaction's balance effect
            $oldAccount = $transaction->account;
            if ($transaction->type === 'expense') {
                $oldAccount->balance += abs($transaction->amount);
            } elseif ($transaction->type === 'income') {
                $oldAccount->balance -= abs($transaction->amount);
            }
            $oldAccount->save();

            // Apply new transaction's balance effect
            $newAccount = Account::find($validated['account_id']);
            if ($validated['type'] === 'expense') {
                $newAccount->balance -= abs($validated['amount']);
            } elseif ($validated['type'] === 'income') {
                $newAccount->balance += abs($validated['amount']);
            }
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

        $accountIds = Account::where('user_id', Auth::id())->pluck('id');
        if (!$accountIds->contains($transaction->account_id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        DB::beginTransaction();
        try {
            // Reverse transaction's balance effect
            $account = $transaction->account;
            if ($transaction->type === 'expense') {
                $account->balance += abs($transaction->amount);
            } elseif ($transaction->type === 'income') {
                $account->balance -= abs($transaction->amount);
            }
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
