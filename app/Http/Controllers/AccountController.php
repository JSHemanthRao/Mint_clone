<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Notification; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AccountController extends Controller
{
    // Get current user ID from Auth or JWT
    private function userId(): ?int
    {
        if (Auth::check()) {
            return Auth::id();
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();
            return $user ? $user->id : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    // Create a notification
    private function notify(int $userId, string $message): void
    {
        Notification::create([
            'user_id' => $userId,
            'message' => $message,
        ]);
    }

    // List all accounts for the user
    public function index()
    {
        $userId = $this->userId();
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(Account::where('user_id', $userId)->get());
    }

    // Store a new account
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'type'            => 'required|string|max:100',
            'initial_balance' => 'nullable|numeric'
        ]);

        $userId = $this->userId();
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated['user_id'] = $userId;
        $validated['balance'] = $validated['initial_balance'] ?? 0;

        $account = Account::create($validated);

        $this->notify($userId, ' New account "' . $account->name . '" created');

        return response()->json($account, 201);
    }

    
    public function show($id)
    {
        $userId = $this->userId();
        $account = Account::where('id', $id)->where('user_id', $userId)->firstOrFail();
        return response()->json($account);
    }


    public function edit($id)
    {
        $account = Account::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('edit', compact('account'));
    }


    public function update(Request $request, $id)
    {
        $userId = $this->userId();
        $account = Account::where('id', $id)->where('user_id', $userId)->firstOrFail();

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'type'    => 'required|string|max:100',
            'balance' => 'required|numeric'
        ]);

        $validated['user_id'] = $userId;
        $account->update($validated);

        $this->notify($userId, ' Account "' . $account->name . '" updated');

        return response()->json($account, 200);
    }


    public function destroy($id)
    {
        $userId = $this->userId();
        $account = Account::where('id', $id)->where('user_id', $userId)->firstOrFail();
        $accountName = $account->name;
        $account->delete();

        $this->notify($userId, ' Account "' . $accountName . '" deleted');

        return response()->json(['message' => 'Account deleted']);
    }

    // Deposit money
    public function deposit(Request $request, $id)
    {
        $userId = $this->userId();
        $request->validate(['amount' => 'required|numeric|min:1']);

        $account = Account::where('id', $id)->where('user_id', $userId)->firstOrFail();
        $amount = (float) $request->amount;

        $account->balance += $amount;
        $account->save();

        $this->notify($userId, 'Deposited ₹' . $amount . ' into "' . $account->name . '"');

        return response()->json($account);
    }


    public function withdraw(Request $request, $id)
    {
        $userId = $this->userId();
        $request->validate(['amount' => 'required|numeric|min:1']);

        $account = Account::where('id', $id)->where('user_id', $userId)->firstOrFail();
        $amount = (float) $request->amount;

        if ($account->balance < $amount) {
            return response()->json(['error' => 'Insufficient funds'], 400);
        }

        $account->balance -= $amount;
        $account->save();

        $this->notify($userId, 'Withdrew ₹' . $amount . ' from "' . $account->name . '"');

        return response()->json($account);
    }
}
