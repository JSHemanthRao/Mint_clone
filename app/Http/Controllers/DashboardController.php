<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Goal;
use App\Models\Transaction;
use Tymon\JWTAuth\Facades\JWTAuth;

class DashboardController extends Controller
{
    public function getData(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $accounts = Account::where('user_id', $user->id)->get();
        $goals = Goal::where('user_id', $user->id)->get();
        $transactions = Transaction::where('user_id', $user->id)
                                   ->latest()
                                   ->take(5) // limit latest 5 transactions
                                   ->get();

        return response()->json([
            'accounts' => $accounts,
            'goals' => $goals,
            'transactions' => $transactions,
        ]);
    }
}
